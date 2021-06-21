<?php

namespace App\Services\Api;

use App\Exceptions;
use App\Repositories as Repos;
use App\Services\Support as Support;
use App\Services\Support\Converter;
use App\Services\Support\Math;

class CalcService
{
    use Support\Logic\CalculationLogic;

    public function getDefaultFlow($form)
    {
        return Converter\VentilatorValueConverter::convertToDefaultFlowResult();
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
        return Converter\VentilatorValueConverter::convertToEstimatedDataResult($estimated_peep, $fio2);
    }

    public function getIeManual($form)
    {
        $i_e_avg = $this->calcIeAvg($form->data);

        $rr = $this->calcRr($i_e_avg['i'], $i_e_avg['e']);

        return Converter\IeConverter::convertToIeResult($i_e_avg['i'], $i_e_avg['e'], $rr);
    }

    public function getIeSound($form)
    {
        $min_sec = 1.5; //取得測定時間下限
        $max_sec = 20; //取得測定時間上限（計算時間比例）

        //音声ファイル作成
        $temp_file = tempnam(sys_get_temp_dir(), 'Tmp');

        $handle = fopen($temp_file, 'w');
        fwrite($handle, base64_decode($form->sound->file_data));

        fclose($handle);

        $wave_data = Support\WaveUtil::extractWaveData($temp_file,$max_sec);

        unlink($temp_file);

        $sr = $wave_data->sampling_rate;
        $dt = 1 / $sr;

        if($dt*$wave_data->length < $min_sec){
                $form->addError('sound','validation.not_enough_recording_time');
                throw new Exceptions\InvalidFormException($form);
        }

        //正規化
        $y_max = max(max($wave_data->func[0]),-min($wave_data->func[0]));
        $func = array_map(function ($x) use ($y_max) {
            return $x / $y_max;
        }, $wave_data->func[0]);

        $n = 2; //呼吸音取得サンプル数設定（2回分の平均をとる）

        $step = 1024;//step間隔を落とすと時間分解能が上がるが、雑音による精度減少が目立つ(TODO要検討)
        $win_length = 1024; //窓幅を大きくすると周波数分解能があがりダイナミックレンジがさがる

        //音量によるie解析測定
        //※注意点
        //電圧計算あたりが完璧か不明
        //分類の偏差値50が適当か
        //雑音入ったときに呼気の誤検知ありえる。連続区間で5区間以上など吸気と同じように処理する必要ありそう
        //結果セットで呼気、吸気のバランスみて同じペースじゃなかったら1サイクル増やして正しい2回を検出するようにしたほうがよいかも
        //=呼気吸気の異常値判定(TODO)
        

        $db_at_t = [];
        for ($i = 0; $i * $step <= $sr * $max_sec; $i++) {
            $sliced_func = array_slice($func, $i * $step, $win_length);
            $V = 0;
            foreach ($sliced_func as $v) {
                $V += $v * $v;
            }
            if (count($sliced_func) === 0) {
                continue;
            }
            $wined_V_avg = 1 / count($sliced_func) * $V;
            $calc_v = sqrt($wined_V_avg);
            //電圧からデシベルへ
            $calc_db = log10($calc_v);
            $db_at_t[round($i * $step * $dt, 2) * 100] = $calc_db;
        }
        //標準偏差
        $length = count($db_at_t);
        $average = array_sum($db_at_t) / count($db_at_t);

        $sum = 0;
        foreach ($db_at_t as $t) {
            $sum += pow(abs($t - $average), 2);
        }

        $start = false; //2周期計測開始判定。1回目のinh_counter開始時にtrue
        $inh_start_ = false; //呼気からスタートしている判定用。吸気5回で呼気判定スタート。
        $inh_start_counter = 0;
        $exh_times = 0; //2周期カウント用
        $inh_times = 0; //2周期カウント用=exh_times(3)でもOK？
        //1024=0.02秒の対象区画($sr=44100の場合)が呼気、吸気のそれぞれに加算された回数
        $inh = 0;
        $exh = 0;

        $exh_counter = 0;

        //偏差値が50を下回る音量が$switch_time_bufferだけ続いたら確定してその分をinhに追加。途中でまた音がしたら（=たぶんカチ）の場合はexhに加算。
        $switch_time_buffer = 0.1;
        $inh_counter_threshold = 10;
        $inh_start_threshold = 20;


        $message = "";

        //結果確認用
        $all_result_message = "";
        foreach ($db_at_t as $k => $v) {
            //echo ($k/100)."<br />";

            $standard_score = ($v - $average) / sqrt($sum / $length) * 10 + 50;
            $threshold = 50;
            if ($standard_score > $threshold && $inh_start_) { //偏差値50以上は呼気またはカチと判断。調整検討要
                $start = true; //測定範囲対象フラグON
                $exh_counter++; //吸気
                if ($exh_counter == 1) {
                    $start_at = ($k / 100);
                    $exh_times++;
                    if ($exh_times == 3) {
                        $message .= "<br />end with 3rd loop on " . $start_at;
                        break;
                    }
                    $message .= $start_at . "～";
                }
                $exh++;
                if (isset($inh_counter)) { //カチの前に無音があって下でカウントしていた分戻す用処理。
                    $exh += $inh_counter;
                    unset($inh_counter);
                }
                $all_result_message .= ($k / 100) . "\t" . $standard_score . "<br />";
            } else {
                $all_result_message .= "(" . ($k / 100) . ")<br />";
                if ($start) { //呼気から開始サイクルとする。
                    //吸気終了間際の無音対策 exh_counterがセットされていたらカチの前の無音処理として呼気にするため溜めておいて一気に処理。
                    if ($exh_counter > 1) {
                        if (!isset($inh_counter)) {
                            $inh_counter = 0;
                            $end_at = ($k / 100);
                        }
                        $inh_counter++;
                        //5区画=0.1秒続いたら確定してその分を追加。途中でまた音がしたら（=たぶんカチ）の場合はexhに加算。
                        if ($inh_counter >  $inh_counter_threshold) {
                            $exh_counter = 0;
                            $inh += $inh_counter;
                            unset($inh_counter);
                            $message .= $end_at . "<br />";
                            $inh_times++; //inh_times加算。
                        }
                    } else {
                        //呼気カウンタなしなら5区画以上の連続なのでそのまま吸気としてそのままカウント
                        $inh++;
                    }
                } else {
                    //10区間無音で吸気からの録音部分判定。この判定後に呼気の開始検出を始める。
                    if ($standard_score < $threshold) {
                        $inh_start_counter++;
                        if ($inh_start_counter == $inh_start_threshold) {
                            $inh_start_ = true;
                        }
                    }
                }
            }
        }

        // echo $all_result_message;

        $i_e_avg = ['i' => round($inh * $step * $dt / $n, 2), 'e' => round($exh * $step * $dt / $n, 2)];

        if($i_e_avg['i']*$i_e_avg['e']===0.0){
                $form->addError('sound','validation.not_enough_pulses');
                throw new Exceptions\InvalidFormException($form);
        }

        $rr = $this->calcRr($i_e_avg['i'], $i_e_avg['e']);

        return Converter\IeConverter::convertToIeResult($i_e_avg['i'], $i_e_avg['e'], $rr);
    }

    //音声サンプリングテスト用
    public function putIeSound($form)
    {
        $result =  Support\FileUtil::putSoundSamplingFile($form->sound->filename, base64_decode($form->sound->file_data), $form->os);

        return $result ? ['result' => 'success'] : ['result' => 'failure'];
    }
}
