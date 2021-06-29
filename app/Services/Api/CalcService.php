<?php

namespace App\Services\Api;

use App\Exceptions;
use App\Repositories as Repos;
use App\Services\Support as Support;
use App\Services\Support\Converter;
use App\Services\Support\DateUtil;
use App\Services\Support\Math;
use App\Services\Support\Statistic;

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
        \Log::debug('--getIeSoundStart--'.DateUtil::now());
        $min_sec = 2.0; //取得測定時間下限
        $max_sec = 20; //取得測定時間上限（計算時間比例）
        $cycle = 4; //呼吸音取得サンプル数
        $cycle_min = 3; //最低呼吸音取得サンプル数

        //音声ファイル作成
        $temp_file = tempnam(sys_get_temp_dir(), 'Tmp');

        $handle = fopen($temp_file, 'w');
        fwrite($handle, base64_decode($form->sound->file_data));

        fclose($handle);

        $wave_data = Support\WaveUtil::extractWaveData($temp_file,$max_sec);

        unlink($temp_file);
        \Log::debug('--tempFileWritten--'.DateUtil::now());
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

        $step = 256;//step間隔を落とすと時間分解能が上がるが、雑音による精度減少が目立つ(TODO要検討)
        $win_length = 1024; //窓幅を大きくすると周波数分解能があがりダイナミックレンジがさがる

        //音量によるie解析測定
        //※注意点
        //電圧計算あたりが完璧か不明
        //分類の偏差値50が適当か
        //雑音入ったときに呼気の誤検知ありえる。連続区間で5区間以上など吸気と同じように処理する必要ありそう
        //結果セットで呼気、吸気のバランスみて同じペースじゃなかったら1サイクル増やして正しい2回を検出するようにしたほうがよいかも
        //=呼気吸気の異常値判定(TODO)
        

        \Log::debug('--analyzeStart--'.DateUtil::now());

        $db_arr = [];
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
            $db_arr[] = $calc_db;
        }

        $statistic = new Statistic($db_arr);

        \Log::debug('--dbArrCollected--'.DateUtil::now());


        $standard_score_threshold = 50;
        $standard_score_threshold_under = 48;
        $standard_score_gap_threshold = 20; //直近$k_2回(>k_1回)のうちの最大値との偏差値差がこれ以上である場合に吸気移行判定
        $standard_score_history = []; //過去n回分の偏差値ストック
        $n = 20; //この分だけ無音判定が続いて初めて計測開始
        $m = 20; //この分だけ呼気判定が続いて初めて呼気ストックに追加
        $k_1 = 5; //この分だけ無音判定が続いて初めてギャップ判定
        $k_2 = 20; //この分のストックの最大値を現在の値と比べ、$standard_score_gap_threshold以上差があれば吸気ストック追加
        //過去10回のうち8回しきい値超えていれば…みたいな判定方法もありかも（要検討）
        $hear = false; //受付開始
        $mode = 0; //0:吸気中,1:呼気中

        $last_exh_start_at = null; //最後に呼気開始検出したインデックス
        $last_inh_start_at = null; //最後に吸気開始検出したインデックス
        $exh_times = [];
        $inh_times = [];

        $error_allowable = 10;//集めた呼気吸気時間に対してそれぞれ偏差値をとり、その50からの差でふるいをかける


        \Log::debug('--analyzeEachDb--'.DateUtil::now());

        foreach ($db_arr as $key => $value) {
            $standard_score = $statistic->standardScore($value);
            $standard_score_history[] = $standard_score;
            if (count($standard_score_history) < max($n, $m, $k_2)) {

                continue;
            }
            if ($hear === true) {
                if ($mode === 0 && min(array_slice($standard_score_history, -$m, $m)) > $standard_score_threshold) {
                    //吸気中に過去m回すべてしきい値を上回れば呼気移行と判定
                    $last_exh_start_at = $key - $m + 1;
                    $mode = 1;
                    if ($last_inh_start_at !== null) {
                        $inh_times[] = ($last_exh_start_at - $last_inh_start_at) * $step * $dt;
                    }
                    //print_r(array_slice($standard_score_history, -$m, $m));
                    //print_r("last_exh_start_at" . $last_exh_start_at . "\n");
                } else if (
                    $mode === 1
                    && max(array_slice($standard_score_history, -$k_1, $k_1)) < $standard_score_threshold_under
                    && max(array_slice($standard_score_history, -$k_2, $k_2)) - $standard_score > $standard_score_gap_threshold
                ) {
                    $last_inh_start_at = $key - $k_1 + 1;
                    $mode = 0;
                    $exh_times[] = ($last_inh_start_at - $last_exh_start_at) * $step * $dt;
                    //print_r("last_inh_start_at" . $last_inh_start_at . "\n");
                }
            } else if (max(array_slice($standard_score_history, -$n, $n)) < $standard_score_threshold_under) {
                $hear = true;
                // print_r($key . "\n");
            }

            if (count($inh_times) === $cycle) break;
        }

        if(count($inh_times)<$cycle_min){
            $form->addError('sound','validation.not_enough_pulses');
            throw new Exceptions\InvalidFormException($form);
        }

        \Log::debug('--dataCleaningStart--'.DateUtil::now());

        $exh_times_statistic = new Statistic($exh_times);
        $inh_times_statistic = new Statistic($inh_times);
        /**
         * 外れ値削除
         */
        foreach($exh_times as $key => $exh_time){
            if(abs($exh_times_statistic->standardScore($exh_time)-50)>$error_allowable){
                unset($exh_times[$key]);
            }
        }

        foreach($inh_times as $inh_time){
            if(abs($inh_times_statistic->standardScore($inh_time)-50)>$error_allowable){
                unset($inh_times[$key]);
            }
        }

        $exh_times_statistic_good = new Statistic($exh_times);
        $inh_times_statistic_good = new Statistic($inh_times);

        $i_e_avg = ['i' => round($inh_times_statistic_good->mean, 2), 'e' => round($exh_times_statistic_good->mean, 2)];

        if($i_e_avg['i']*$i_e_avg['e']===0.0){
                $form->addError('sound','validation.not_enough_pulses');
                throw new Exceptions\InvalidFormException($form);
        }

        $rr = $this->calcRr($i_e_avg['i'], $i_e_avg['e']);

        \Log::debug('--analyzeDone--'.DateUtil::now());

        return Converter\IeConverter::convertToIeResult($i_e_avg['i'], $i_e_avg['e'], $rr);
    }

    //音声サンプリングテスト用
    public function putIeSound($form)
    {
        $result =  Support\FileUtil::putSoundSamplingFile($form->sound->filename, base64_decode($form->sound->file_data), $form->os);

        return $result ? ['result' => 'success'] : ['result' => 'failure'];
    }
}
