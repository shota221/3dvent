<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * 方針：区間ごとのボリュームマップおよびその偏差値を求める
 */
class VolumeDetectTest extends TestCase
{
   
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $max_sec = 20; //取得測定時間上限（計算時間比例）

        foreach ($this->sound_files as $code) {

            $fn = '../analyze/sound_file/' . $code . '.wav';
            $wave_data = WaveUtil::extractWaveData($fn, $max_sec);
            $y_max = max(max($wave_data->func[0]), -min($wave_data->func[0]));

            $func = array_map(function ($x) use ($y_max) {
                return $x / $y_max;
            }, $wave_data->func[0]);

            $sr = $wave_data->sampling_rate;
            $dt = 1 / $sr;
            $n = 2; //呼吸音取得サンプル数指定

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

            //書き出し
            $stream = fopen('../analyze/' . $code . '-volume_standard_score2.csv', 'w');
            foreach ($db_arr as $key => $value) {
                fputcsv($stream, [$key, $statistic->standardScore($value)]);
            }
            fclose($stream);
        }
    }

    private $sound_files = [
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
        '3_MV002_SN210202-16_8cmH2O_5LPM_20210506'

    ];
 
}
