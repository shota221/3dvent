<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * 方針：n周期分計測。呼気吸気それぞれの偏差値を求め外れ値を検出
 */
class ExampleTest4 extends TestCase
{
    public function testBasicTest()
    {
        $max_sec = 20; //取得測定時間上限（計算時間比例）

        $cycle = 4; //取得周期数

        $code = '3_MV002_SN210202-16_8cmH2O_12LPM_20210506';

        $fn = '../analyze/sound_file/' . $code . '.wav';
        $wave_data = WaveUtil::extractWaveData($fn, $max_sec);
        $y_max = max(max($wave_data->func[0]), -min($wave_data->func[0]));

        $func = array_map(function ($x) use ($y_max) {
            return $x / $y_max;
        }, $wave_data->func[0]);

        $sr = $wave_data->sampling_rate;
        $dt = 1 / $sr;

        $wave_length = $wave_data->length;
        $step = 256; //second/step=$step*$dt
        $win_length = 1024; //窓幅を大きくすると周波数分解能があがりダイナミックレンジがさがる

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

        $error_allowable = 10;

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
                    print_r(array_slice($standard_score_history, -$m, $m));
                    print_r("last_exh_start_at" . $last_exh_start_at . "\n");
                } else if (
                    $mode === 1
                    && max(array_slice($standard_score_history, -$k_1, $k_1)) < $standard_score_threshold_under
                    && max(array_slice($standard_score_history, -$k_2, $k_2)) - $standard_score > $standard_score_gap_threshold
                ) {
                    $last_inh_start_at = $key - $k_1 + 1;
                    $mode = 0;
                    $exh_times[] = ($last_inh_start_at - $last_exh_start_at) * $step * $dt;
                    print_r("last_inh_start_at" . $last_inh_start_at . "\n");
                }
            } else if (max(array_slice($standard_score_history, -$n, $n)) < $standard_score_threshold_under) {
                $hear = true;
                print_r($key . "\n");
            }

            if (count($inh_times) === $cycle) break;
        }

        $exh_times_statistics = new Statistic($exh_times);
        $inh_times_statistics = new Statistic($inh_times);

        print_r($exh_times);
        print_r($inh_times);
        
        /**
         * 外れ値削除
         */
        foreach($exh_times as $key => $exh_time){
            if(abs($exh_times_statistics->standardScore($exh_time)-50)>$error_allowable){
                unset($exh_times[$key]);
            }
        }

        foreach($inh_times as $inh_time){
            if(abs($inh_times_statistics->standardScore($inh_time)-50)>$error_allowable){
                unset($inh_times[$key]);
            }
        }

        $exh_times_statistics = new Statistic($exh_times);
        $inh_times_statistics = new Statistic($inh_times);
        
        print_r($exh_times_statistics->mean."\n");
        print_r($inh_times_statistics->mean."\n");

    }
}
