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
use App\Services\Support\FileUtil;
use Illuminate\Support\Facades\Storage;

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

        //以下解析処理詳細はTest/ExampleTest2参照
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
        $cool_time = 100; //ピーク検出後に飛ばすインデックス
        $exhs = []; //呼気：小さめのピーク
        $inhs = []; //吸気：大きめのピーク

        $mode = false; //falseのときカチ音検出モード、trueでシュー音検出モード

        for ($i = 0; $i * $step <= min($wave_length - $win_length, $sr * $max_sec); $i++) {
            $peak_indice = [];
            $sliced_func = array_slice($func, $i * $step, $win_length);
            $wined_func = array_map($blackman, array_keys($sliced_func), $sliced_func);

            $fftfunc = $fft->fft($wined_func);
            $fftabs = $fft->getAbsFFT($fftfunc);

            $peak_indice = $this->findPeakIndex(array_slice($fftabs, 0, 128), $mode);

            if (
                count($peak_indice) >= ($mode ? 2 : 3)
            ) {
                $pulse_times[] = round($i * $step * $dt, 2);
                $pulse_count++;
                $i += $cool_time; //クールタイム分とばす
                $mode = !$mode;
            }

            //パルスが2*$n+1カウントされた時点で終了
            if ($pulse_count === 2 * $n + 1) break;
        }

        if ($pulse_count < 2 * $n + 1) {
            //音が小さすぎるor測定時間が短すぎる
            $form->addError('sound', 'validation.not_enough_pulses');
            return false;
        }

        for ($i = 0; $i < $n; $i++) {
            $inhs[] = $pulse_times[2 * $i + 1] - $pulse_times[2 * $i];
            $exhs[] = $pulse_times[2 * $i + 2] - $pulse_times[2 * $i + 1];
        }

        $i_e_avg = ['i' => round(array_sum($inhs) / $n, 2), 'e' => round(array_sum($exhs) / $n, 2)];

        $rr = $this->calcRr($i_e_avg['i'], $i_e_avg['e']);

        return Converter\IeConverter::convertToIeResult($i_e_avg['i'], $i_e_avg['e'], $rr);
    }

    private function findPeakIndex($func, bool $mode = false)
    {
        $peaks = [];

        for ($i = 1; $i < count($func) - 1; $i++) {
            $check = $this->checkThreshold($i, $func[$i], $mode);
            if (
                $check >= 0
                && $func[$i - 1] <= $func[$i]
                && $func[$i + 1] <= $func[$i]
            ) {
                $peaks[$check] = $i;
            }
        }

        return $peaks;
    }

    /**
     * modeがfalseならカチッ音、trueならシュー音検出
     * 検知したしきい値のインデックスを返す。検知してなければ-1をかえす
     * @param [type] $i
     * @param [type] $val
     * @param boolean $mode
     * @return void
     */
    private function checkThreshold($i, $val, bool $mode = false)
    {
        $threshold = $mode ? config('analysis.threshold.ex') : config('analysis.threshold.in');

        foreach ($threshold as $key => $thr) {
            if (
                $i >= $thr['index_min']
                && $i <= $thr['index_max']
                && $val >= $thr['amp']
            ) {
                return $key;
            }
        }
        return -1;
    }


    public function putIeSound($form)
    {
        $result =  Support\FileUtil::putSoundSamplingFile($form->sound->filename,base64_decode($form->sound->file_data),$form->os);

        return $result;
    }
}
