<?php

namespace App\Services\Api;

use App\Exceptions;
use App\Models;
use App\Http\Forms\Api as Form;
use App\Http\Response as Response;
use App\Repositories as Repos;
use App\Models\Report;
use App\Services\Support as Support;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Services\Support\Converter;
use App\Services\Support\Math;

class CalcService
{
    use Support\Logic\CalculationLogic;

    public function getDefaultFlow($form)
    {
        return Converter\VentilatorConverter::convertToDefaultFlowResult();
    }

    public function getEstimatedData($form)
    {
        $estimated_peep = !is_null($form->airway_pressure) ? $this->calcEstimatedPeep(floatval($form->airway_pressure)) : null;

        if (!is_null($form->air_flow) && !is_null($form->o2_flow)) {
            if (floatval($form->air_flow) + floatval($form->o2_flow) === 0.0) {
                $fio2 = 0.0;
            } else {
                $fio2 = $this->calcFio2(floatval($form->air_flow), floatval($form->o2_flow));
            }
        } else {
            $fio2 = null;
        }
        return Converter\VentilatorConverter::convertToEstimatedDataResult($estimated_peep, $fio2);
    }

    public function getIeManual($form)
    {
        $i_e_avg = $this->calcIeAvg($form->data);

        $rr = $this->calcRr($i_e_avg['i'], $i_e_avg['e']);

        return Converter\IeConverter::convertToIeResult($i_e_avg['i'], $i_e_avg['e'], $rr);
    }

    public function getIeSound($form)
    {
        //音声ファイル作成
        $temp_file = tempnam(sys_get_temp_dir(), 'Tmp');

        $handle = fopen($temp_file, 'w');
        fwrite($handle, base64_decode($form->sound->file_data));
        
        fclose($handle);

        $wave_data = Support\WaveUtil::extractWaveData($temp_file);

        unlink($temp_file);

        //以下解析処理
        //正規化
        $y_max = max($wave_data->func[0]);
        $func = array_map(function ($x) use ($y_max) {
            return $x / $y_max;
        }, $wave_data->func[0]);

        $n = 2; //呼吸音取得サンプル数設定（2回分の平均をとる）

        $max_sec = 15; //取得測定時間上限（計算時間比例）

        $sr = $wave_data->sampling_rate;
        $dt = 1 / $sr;
        $wave_length = $wave_data->length;
        $step = 128; //second/step=$step*$dt
        $win_length = 256; //窓幅を大きくすると周波数分解能があがりダイナミックレンジがさがる
        $df = $sr / $win_length; //周波数分解幅
        //$f_a~$f_bの周波数についての変化をとる
        $f_a = 11000;
        $f_b = $sr / 2;

        $i_a = floor($f_a / $df);
        $i_b = floor($f_b / $df);

        //窓関数準備
        $blackman = function ($index, $value) use ($win_length) {
            return $value * (0.42 - (0.5 * cos(2 * M_PI * $index / ($win_length - 1))) + 0.08 * cos(4 * M_PI * $index / ($win_length - 1)));
        }; //ブラックマン窓は周波数分解能が悪く、ダイナミック・レンジが広い。この種のフィルタの中では最もよく使われる、らしい。
        // $hann = function ($index, $value) use ($win_length) {
        //     return $value * (0.5 - (0.5 * cos(2 * M_PI * $index / ($win_length - 1))));
        // };
        // $hamming = function ($index, $value) use ($win_length) {
        //     return $value * (0.54 - (0.46 * cos(2 * M_PI * $index / ($win_length - 1))));
        // };

        $fft = new Math\Fft($win_length);

        $pulse_count = 0;
        $pulse_times = [];
        $pulses = [];
        $hear_flg = 1; //1のときヒア状態。ピーク検出で0に。移動平均線がある数を下回ると再び1になる
        $peak_th = -1.5; //周波数帯$f_a~$f_bの和の対数を評価してピーク検出
        $hear_th = -1; //周波数帯$f_a~$f_bの移動平均線(*$m)の対数がこれを下回ったときに再びピーク検出可となる
        $cool_time = 100; //ピーク検出後に飛ばすインデックス
        $ma = []; //移動平均配列(5)
        $m = 5; //移動平均線の平均値取得幅
        $exhs = []; //呼気：小さめのピーク
        $inhs = []; //吸気：大きめのピーク


        //短時間フーリエ変換
        for ($i = 0; $i * $step <= min($wave_length - $win_length, $sr * $max_sec); $i++) {
            $sliced_func = array_slice($func, $i * $step, $win_length);
            $wined_func = array_map($blackman, array_keys($sliced_func), $sliced_func);

            $fftfunc = $fft->fft($wined_func);
            $fftabs = $fft->getAbsFFT($fftfunc);


            $sum = 0;
            for ($j = $i_a; $j <= $i_b; $j++) {
                $sum += $fftabs[$j];
            }

            $sum_log = log10($sum);

            if ($sum_log >= $peak_th && $hear_flg === 1) {
                $pulse_times[] = round($i * $step * $dt, 2);
                $pulses[] = $sum_log;
                $pulse_count++;
                $i += $cool_time; //クールタイム分とばす
            }

            //パルスが2*$n+1カウントされた時点で終了
            if ($pulse_count === 2 * $n + 1) break;

            $ma[$i % $m] = $sum;
            if ($i < $m) continue;
            if ($hear_flg === 0 && log10(array_sum($ma)) <= $hear_th) $hear_flg = 1; //移動平均線が$hear_thを下回ったときにピーク受け入れ状態になる

        }

        if (count($pulses) < 2 * $n + 1) {
            //音が小さすぎるor測定時間が短すぎる
            $form->addError('sound', 'validation.not_enough_pulses');
            return false;
        }

        if ($pulses[0] < $pulses[1]) {
            //呼気スタート音のほうが小さい
            for ($i = 0; $i < $n; $i++) {
                if ($pulses[2 * $i + 1] < $pulses[2 * $i] || $pulses[2 * $i + 1] < $pulses[2 * $i + 2]) {
                    //パルスの大小が不規則->クリック音が正しく取得されていない＝雑音が大きい
                    $form->addError('sound', 'validation.invalid_sound');
                    return false;
                }
                $exhs[] = $pulse_times[2 * $i + 1] - $pulse_times[2 * $i];
                $inhs[] = $pulse_times[2 * $i + 2] - $pulse_times[2 * $i + 1];
            }
        } else {
            for ($i = 0; $i < $n; $i++) {
                if ($pulses[2 * $i + 1] > $pulses[2 * $i] || $pulses[2 * $i + 1] > $pulses[2 * $i + 2]) {
                    //パルスの大小が不規則->クリック音が正しく取得されていない＝雑音が大きい
                    $form->addError('sound', 'validation.invalid_sound');
                    return false;
                }
                $inhs[] = $pulse_times[2 * $i + 1] - $pulse_times[2 * $i];
                $exhs[] = $pulse_times[2 * $i + 2] - $pulse_times[2 * $i + 1];
            }
        }

        $i_e_avg = ['i' => round(array_sum($inhs) / $n, 3), 'e' => round(array_sum($exhs) / $n, 3)];

        $rr = $this->calcRr($i_e_avg['i'], $i_e_avg['e']);

        return Converter\IeConverter::convertToIeResult($i_e_avg['i'], $i_e_avg['e'], $rr);
    }
}
