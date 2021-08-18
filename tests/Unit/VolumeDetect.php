<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * 方針：n周期分計測。呼気吸気それぞれの偏差値を求め外れ値を検出
 */
class VolumeDetect extends TestCase
{
    public function testBasicTest()
    {
        $stream = fopen('../analyze/volume_analysis.csv', 'w');

        $header = ['file_name', 'in', 'ex', 'rr'];

        fputcsv($stream, $header);

        foreach ($this->sound_files as $code) {
            try {
                $this->putIeMeans($code, $stream);
            } catch (\Throwable $e) {
                fputcsv($stream, [$code, 'analysis_failure']);
            }
        }
    }

    public function putIeMeans($code, $stream)
    {
        $max_sec = 25; //取得測定時間上限（計算時間比例）

        $cycle = 5; //取得周期数

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

        $exh_start_at = []; //呼気開始検出したインデックス
        $exh_start_count = 0;
        $inh_start_at = []; //吸気開始検出したインデックス
        $inh_start_count = 0;
        $exh_times = [];
        $inh_times = [];

        $error_allowable = 15; //集めた呼気吸気時間に対してそれぞれ偏差値をとり、その50からの差でふるいをかける
        $click_allowable_time = 0.2; //呼気終了後であってもこの時間分だけはカチ音を受け付ける。
        $click_allowable_index = floor($click_allowable_time / $dt / $step);
        $click_threshold = 60; //呼気判定に入ってから$click_allowable_indexすぎるまでにこれを偏差値で上回る点があればクリック音として判定

        foreach ($db_arr as $key => $value) {
            $standard_score = $statistic->standardScore($value);
            $standard_score_history[] = $standard_score;
            if (count($standard_score_history) < max($n, $m, $k_2)) {

                continue;
            }
            if ($hear === true) {
                if ($mode === 0) {
                    if (min(array_slice($standard_score_history, -$m, $m)) > $standard_score_threshold) {
                        //吸気中に過去m回すべてしきい値を上回れば呼気移行と判定
                        $exh_start_at[] = $key - $m + 1;
                        $exh_start_count++;
                        $mode = 1;
                    } else if (!empty($inh_start_at) && $key - $inh_start_at[array_key_last($inh_start_at)] <= $click_allowable_index && $standard_score > $click_threshold) {
                        //吸気中と判定していたがクリック音が検出された場合の処理
                        $inh_start_at[array_key_last($inh_start_at)] = $key;
                    }
                } else if (
                    $mode === 1
                    && max(array_slice($standard_score_history, -$k_1, $k_1)) < $standard_score_threshold_under
                    && max($gap_judge_array = array_slice($standard_score_history, -$k_2, $k_2)) - $standard_score > $standard_score_gap_threshold
                ) {
                    $maxes = array_keys($gap_judge_array, max($gap_judge_array));
                    $inh_start_at[] = $key - $k_2 + 1 + $maxes[0];
                    $inh_start_count++;
                    $mode = 0;
                }
            } else if (max(array_slice($standard_score_history, -$n, $n)) < $standard_score_threshold_under) {
                $hear = true;
            }

            if ($inh_start_count === $cycle) break;
        }

        for ($i = 0; $i < $inh_start_count; $i++) {
            $exh_times[] = ($inh_start_at[$i] - $exh_start_at[$i]) * $step * $dt;
        }

        for ($i = 1; $i < $exh_start_count; $i++) {
            $inh_times[] = ($exh_start_at[$i] - $inh_start_at[$i - 1]) * $step * $dt;
        }

        $exh_times_statistics = new Statistic($exh_times);
        $inh_times_statistics = new Statistic($inh_times);

        /**
         * 外れ値削除(4サンプル集まって初めて機能(3サンプルだと偏差値がかんたんにしきい値をまたいでしまうため))
         */
        if (count($exh_times) >= 4) {
            foreach ($exh_times as $key => $exh_time) {
                if (abs($exh_times_statistics->standardScore($exh_time) - 50) > $error_allowable) {
                    unset($exh_times[$key]);
                    unset($exh_start_at[$key]);
                }
            }
        }

        if (count($inh_times) >= 4) {
            foreach ($inh_times as $key => $inh_time) {
                if (abs($inh_times_statistics->standardScore($inh_time) - 50) > $error_allowable) {
                    unset($inh_times[$key]);
                    unset($inh_start_at[$key]);
                }
            }
        }

        $exh_times_statistics_good = new Statistic($exh_times);
        $inh_times_statistics_good = new Statistic($inh_times);

        $i_avg = $inh_times_statistics_good->mean;
        $e_avg = $exh_times_statistics_good->mean;
        $rr = $this->calcRr($i_avg, $e_avg);

        fputcsv($stream, [$code, $i_avg, $e_avg, $rr]);
    }

    public function calcRr(float $i_avg, float $e_avg)
    {
        return round(60 / ($e_avg + $i_avg), 2);
    }

    private $sound_files = [
        '1_MV002_SN210202-16_10cmH2O_10LPM_20210616',
        '2_MV002_SN210202-16_30cmH2O_27LPM_20210616',
        '2_MV002_SN210202-16_20cmH2O_20LPM_20210616',
        '2_MV002_SN210202-16_10cmH2O_5LPM_20210616',
        '3_MV002_SN210202-16_40cmH2O_20LPM_20210616',
        '3_MV002_SN210202-16_20cmH2O_8.5LPM_20210616',
        '3_MV002_SN210202-16_15cmH2O_5LPM_20210616',
        '4_MV002_SN210202-16_15cmH2O_15LPM_20210616',
        '4_MV002_SN210202-16_9cmH2O_5LPM_20210616',
        '5_MV002_SN210202-16_30cmH2O_27LPM_20210616',
        '5_MV002_SN210202-16_25cmH2O_15LPM_20210616',
        '5_MV002_SN210202-16_20cmH2O_9LPM_20210616',
        '5_MV002_SN210202-16_15cmH2O_5LPM_20210616',
        '6_MV002_SN210202-16_45cmH2O_22LPM_20210616',
        '6_MV002_SN210202-16_40cmH2O_18LPM_20210616',
        '4_MV002_SN210202-16_25cmH2O_7LPM_210529',
        '4_MV002_SN210202-16_20cmH2O_7LPM_210529',
        '4_MV002_SN210202-16_15cmH2O_7LPM_210529',
        '4_MV002_SN210202-16_10cmH2O_7LPM_210529',
        '4_MV002_SN210202-16_8cmH2O_7LPM_210529',
        '4_MV002_SN210202-16_20cmH2O_5LPM_210529',
        '4_MV002_SN210202-16_15cmH2O_5LPM_210529',
        '4_MV002_SN210202-16_10cmH2O_5LPM_210529',
        '4_MV002_SN210202-16_8cmH2O_5LPM_210529',
        '5_MV002_SN210202-16_45cmH2O_30LPM_210529',
        '5_MV002_SN210202-16_40cmH2O_30LPM_210529',
        '5_MV002_SN210202-16_35cmH2O_30LPM_210529',
        '5_MV002_SN210202-16_30cmH2O_30LPM_210529',
        '5_MV002_SN210202-16_45cmH2O_27LPM_210529',
        '5_MV002_SN210202-16_40cmH2O_27LPM_210529',
        '5_MV002_SN210202-16_35cmH2O_27LPM_210529',
        '5_MV002_SN210202-16_30cmH2O_27LPM_210529',
        '5_MV002_SN210202-16_25cmH2O_27LPM_210529',
        '5_MV002_SN210202-16_45cmH2O_24LPM_210529',
        '5_MV002_SN210202-16_40cmH2O_24LPM_210529',
        '5_MV002_SN210202-16_35cmH2O_24LPM_210529',
        '5_MV002_SN210202-16_30cmH2O_24LPM_210529',
        '5_MV002_SN210202-16_25cmH2O_24LPM_210529',
        '5_MV002_SN210202-16_45cmH2O_21LPM_210529',
        '5_MV002_SN210202-16_40cmH2O_21LPM_210529',
        '5_MV002_SN210202-16_35cmH2O_21LPM_210529',
        '5_MV002_SN210202-16_30cmH2O_21LPM_210529',
        '5_MV002_SN210202-16_25cmH2O_21LPM_210529',
        '5_MV002_SN210202-16_20cmH2O_21LPM_210529',
        '5_MV002_SN210202-16_45cmH2O_18LPM_210529',
        '5_MV002_SN210202-16_40cmH2O_18LPM_210529',
        '5_MV002_SN210202-16_35cmH2O_18LPM_210529',
        '5_MV002_SN210202-16_30cmH2O_18LPM_210529',
        '5_MV002_SN210202-16_25cmH2O_18LPM_210529',
        '5_MV002_SN210202-16_20cmH2O_18LPM_210529',
        '5_MV002_SN210202-16_15cmH2O_18LPM_210529',
        '5_MV002_SN210202-16_45cmH2O_15LPM_210529',
        '5_MV002_SN210202-16_40cmH2O_15LPM_210529',
        '5_MV002_SN210202-16_35cmH2O_15LPM_210529',
        '5_MV002_SN210202-16_30cmH2O_15LPM_210529',
        '5_MV002_SN210202-16_25cmH2O_15LPM_210529',
        '5_MV002_SN210202-16_20cmH2O_15LPM_210529',
        '5_MV002_SN210202-16_15cmH2O_15LPM_210529',
        '5_MV002_SN210202-16_10cmH2O_15LPM_210529',
        '5_MV002_SN210202-16_45cmH2O_12LPM_210529',
        '5_MV002_SN210202-16_40cmH2O_12LPM_210529',
        '5_MV002_SN210202-16_35cmH2O_12LPM_210529',
        '5_MV002_SN210202-16_30cmH2O_12LPM_210529',
        '5_MV002_SN210202-16_25cmH2O_12LPM_210529',
        '5_MV002_SN210202-16_20cmH2O_12LPM_210529',
        '5_MV002_SN210202-16_15cmH2O_12LPM_210529',
        '5_MV002_SN210202-16_10cmH2O_12LPM_210529',
        '5_MV002_SN210202-16_8cmH2O_12LPM_210529',
        '5_MV002_SN210202-16_40cmH2O_9LPM_210529',
        '5_MV002_SN210202-16_35cmH2O_9LPM_210529',
        '5_MV002_SN210202-16_30cmH2O_9LPM_210529',
        '5_MV002_SN210202-16_25cmH2O_9LPM_210529',
        '5_MV002_SN210202-16_20cmH2O_9LPM_210529',
        '5_MV002_SN210202-16_15cmH2O_9LPM_210529',
        '5_MV002_SN210202-16_10cmH2O_9LPM_210529',
        '5_MV002_SN210202-16_8cmH2O_9LPM_210529',
        '5_MV002_SN210202-16_25cmH2O_7LPM_210529',
        '5_MV002_SN210202-16_20cmH2O_7LPM_210529',
        '5_MV002_SN210202-16_15cmH2O_7LPM_210529',
        '5_MV002_SN210202-16_10cmH2O_7LPM_210529',
        '5_MV002_SN210202-16_8cmH2O_7LPM_210529',
        '5_MV002_SN210202-16_15cmH2O_5LPM_210529',
        '5_MV002_SN210202-16_10cmH2O_5LPM_210529',
        '5_MV002_SN210202-16_8cmH2O_5LPM_210529',
        '6_MV002_SN210202-16_45cmH2O_30LPM_210529',
        '6_MV002_SN210202-16_40mH2O_30LPM_210529',
        '6_MV002_SN210202-16_35mH2O_30LPM_210529',
        '6_MV002_SN210202-16_30cmH2O_30LPM_210529',
        '6_MV002_SN210202-16_45cmH2O_27LPM_210529',
        '6_MV002_SN210202-16_40cmH2O_27LPM_210529',
        '6_MV002_SN210202-16_35cmH2O_27LPM_210529',
        '6_MV002_SN210202-16_30cmH2O_27LPM_210529',
        '6_MV002_SN210202-16_25cmH2O_27LPM_210529',
        '6_MV002_SN210202-16_45cmH2O_24LPM_210529',
        '6_MV002_SN210202-16_40cmH2O_24LPM_210529',
        '6_MV002_SN210202-16_35cmH2O_24LPM_210529',
        '6_MV002_SN210202-16_30cmH2O_24LPM_210529',
        '6_MV002_SN210202-16_25cmH2O_24LPM_210529',
        '6_MV002_SN210202-16_45cmH2O_21LPM_210529',
        '6_MV002_SN210202-16_40cmH2O_21LPM_210529',
        '6_MV002_SN210202-16_35cmH2O_21LPM_210529',
        '6_MV002_SN210202-16_30cmH2O_21LPM_210529',
        '6_MV002_SN210202-16_25cmH2O_21LPM_210529',
        '6_MV002_SN210202-16_20cmH2O_21LPM_210529',
        '6_MV002_SN210202-16_45cmH2O_18LPM_210529',
        '6_MV002_SN210202-16_40cmH2O_18LPM_210529',
        '6_MV002_SN210202-16_35cmH2O_18LPM_210529',
        '6_MV002_SN210202-16_30cmH2O_18LPM_210529',
        '6_MV002_SN210202-16_25cmH2O_18LPM_210529',
        '6_MV002_SN210202-16_20cmH2O_18LPM_210529',
        '6_MV002_SN210202-16_15cmH2O_18LPM_210529',
        '6_MV002_SN210202-16_45cmH2O_15LPM_210528',
        '6_MV002_SN210202-16_40cmH2O_15LPM_210528',
        '6_MV002_SN210202-16_35cmH2O_15LPM_210528',
        '6_MV002_SN210202-16_30cmH2O_15LPM_210528',
        '6_MV002_SN210202-16_25cmH2O_15LPM_210528',
        '6_MV002_SN210202-16_20cmH2O_15LPM_210528',
        '6_MV002_SN210202-16_15cmH2O_15LPM_210528',
        '6_MV002_SN210202-16_10cmH2O_15LPM_210528',
        '6_MV002_SN210202-16_45cmH2O_12LPM_210528',
        '6_MV002_SN210202-16_40cmH2O_12LPM_210528',
        '6_MV002_SN210202-16_35cmH2O_12LPM_210528',
        '6_MV002_SN210202-16_30cmH2O_12LPM_210528',
        '6_MV002_SN210202-16_25cmH2O_12LPM_210528',
        '6_MV002_SN210202-16_20cmH2O_12LPM_210528',
        '6_MV002_SN210202-16_15cmH2O_12LPM_210528',
        '6_MV002_SN210202-16_10cmH2O_12LPM_210528',
        '6_MV002_SN210202-16_8cmH2O_12LPM_210528',
        '6_MV002_SN210202-16_35cmH2O_9LPM_210528',
        '6_MV002_SN210202-16_30cmH2O_9LPM_210528',
        '6_MV002_SN210202-16_25cmH2O_9LPM_210528',
        '6_MV002_SN210202-16_20cmH2O_9LPM_210528',
        '6_MV002_SN210202-16_15cmH2O_9LPM_210528',
        '6_MV002_SN210202-16_10cmH2O_9LPM_210528',
        '6_MV002_SN210202-16_8cmH2O_9LPM_210528',
        '6_MV002_SN210202-16_25cmH2O_7LPM_210528',
        '6_MV002_SN210202-16_20cmH2O_7LPM_210528',
        '6_MV002_SN210202-16_15cmH2O_7LPM_210528',
        '6_MV002_SN210202-16_10cmH2O_7LPM_210528',
        '6_MV002_SN210202-16_8cmH2O_7LPM_210528',
        '6_MV002_SN210202-16_15cmH2O_5LPM_210528',
        '6_MV002_SN210202-16_10cmH2O_5LPM_210528',
        '6_MV002_SN210202-16_8cmH2O_5LPM_210528',
        '3_MV002_SN210202-16_45cmH2O_30LPM_210528',
        '3_MV002_SN210202-16_40cmH2O_30LPM_210528',
        '3_MV002_SN210202-16_35cmH2O_30LPM_210528',
        '3_MV002_SN210202-16_30cmH2O_30LPM_210528',
        '3_MV002_SN210202-16_45cmH2O_27LPM_210528',
        '3_MV002_SN210202-16_40cmH2O_27LPM_210528',
        '3_MV002_SN210202-16_35cmH2O_27LPM_210528',
        '3_MV002_SN210202-16_30cmH2O_27LPM_210528',
        '3_MV002_SN210202-16_25cmH2O_27LPM_210528',
        '3_MV002_SN210202-16_45cmH2O_24LPM_210528',
        '3_MV002_SN210202-16_40cmH2O_24LPM_210528',
        '3_MV002_SN210202-16_35cmH2O_24LPM_210528',
        '3_MV002_SN210202-16_30cmH2O_24LPM_210528',
        '3_MV002_SN210202-16_25cmH2O_24LPM_210528',
        '3_MV002_SN210202-16_20cmH2O_24LPM_210528',
        '3_MV002_SN210202-16_45cmH2O_21LPM_210528',
        '3_MV002_SN210202-16_40cmH2O_21LPM_210528',
        '3_MV002_SN210202-16_35cmH2O_21LPM_210528',
        '3_MV002_SN210202-16_30cmH2O_21LPM_210528',
        '3_MV002_SN210202-16_25cmH2O_21LPM_210528',
        '3_MV002_SN210202-16_20cmH2O_21LPM_210528',
        '3_MV002_SN210202-16_45cmH2O_18LPM_210528',
        '3_MV002_SN210202-16_40cmH2O_18LPM_210528',
        '3_MV002_SN210202-16_35cmH2O_18LPM_210528',
        '3_MV002_SN210202-16_30cmH2O_18LPM_210528',
        '3_MV002_SN210202-16_25cmH2O_18LPM_210528',
        '3_MV002_SN210202-16_20cmH2O_18LPM_210528',
        '3_MV002_SN210202-16_15cmH2O_18LPM_210528',
        '3_MV002_SN210202-16_45cmH2O_15LPM_210528',
        '3_MV002_SN210202-16_40cmH2O_15LPM_210528',
        '3_MV002_SN210202-16_35cmH2O_15LPM_210528',
        '3_MV002_SN210202-16_30cmH2O_15LPM_210528',
        '3_MV002_SN210202-16_25cmH2O_15LPM_210528',
        '3_MV002_SN210202-16_20cmH2O_15LPM_210528',
        '3_MV002_SN210202-16_15cmH2O_15LPM_210528',
        '3_MV002_SN210202-16_10cmH2O_15LPM_210528',
        '3_MV002_SN210202-16_35cmH2O_15LPM_20210511',
        '3_MV002_SN210202-16_30cmH2O_15LPM_20210511',
        '3_MV002_SN210202-16_25cmH2O_15LPM_20210511',
        '3_MV002_SN210202-16_20cmH2O_15LPM_20210511',
        '3_MV002_SN210202-16_15cmH2O_15LPM_20210511',
        '3_MV002_SN210202-16_10cmH2O_15LPM_20210511',
        '3_MV002_SN210202-16_8cmH2O_15LPM_20210511',
        '3_MV002_SN210202-16_45cmH2O_12LPM_20210506',
        '3_MV002_SN210202-16_40cmH2O_12LPM_20210506',
        '3_MV002_SN210202-16_35cmH2O_12LPM_20210506',
        '3_MV002_SN210202-16_30cmH2O_12LPM_20210506',
        '3_MV002_SN210202-16_25cmH2O_12LPM_20210506',
        '3_MV002_SN210202-16_20cmH2O_12LPM_20210506',
        '3_MV002_SN210202-16_15cmH2O_12LPM_20210506',
        '3_MV002_SN210202-16_10cmH2O_12LPM_20210506',
        '3_MV002_SN210202-16_8cmH2O_12LPM_20210506',
        '3_MV002_SN210202-16_45cmH2O_9LPM_20210506',
        '3_MV002_SN210202-16_40cmH2O_9LPM_20210506',
        '3_MV002_SN210202-16_35cmH2O_9LPM_20210506',
        '3_MV002_SN210202-16_30cmH2O_9LPM_20210506',
        '3_MV002_SN210202-16_25cmH2O_9LPM_20210506',
        '3_MV002_SN210202-16_20cmH2O_9LPM_20210506',
        '3_MV002_SN210202-16_15cmH2O_9LPM_20210506',
        '3_MV002_SN210202-16_10cmH2O_9LPM_20210506',
        '3_MV002_SN210202-16_8cmH2O_9LPM_20210506',
        '3_MV002_SN210202-16_45cmH2O_7LPM_20210506',
        '3_MV002_SN210202-16_40cmH2O_7LPM_20210506',
        '3_MV002_SN210202-16_35cmH2O_7LPM_20210506',
        '3_MV002_SN210202-16_30cmH2O_7LPM_20210506',
        '3_MV002_SN210202-16_25cmH2O_7LPM_20210506',
        '3_MV002_SN210202-16_20cmH2O_7LPM_20210506',
        '3_MV002_SN210202-16_15cmH2O_7LPM_20210506',
        '3_MV002_SN210202-16_10cmH2O_7LPM_20210506',
        '3_MV002_SN210202-16_8cmH2O_7LPM_20210506',
        '3_MV002_SN210202-16_45cmH2O_5LPM_20210506',
        '3_MV002_SN210202-16_40cmH2O_5LPM_20210506',
        '3_MV002_SN210202-16_35cmH2O_5LPM_20210506',
        '3_MV002_SN210202-16_30cmH2O_5LPM_20210506',
        '3_MV002_SN210202-16_25cmH2O_5LPM_20210506',
        '3_MV002_SN210202-16_20cmH2O_5LPM_20210506',
        '3_MV002_SN210202-16_15cmH2O_5LPM_20210506',
        '3_MV002_SN210202-16_10cmH2O_5LPM_20210506',
        '3_MV002_SN210202-16_8cmH2O_5LPM_20210506',
        '3_MV002_SN210202-16_15cmH2O_4.5LPM_20210419',
    ];
}
