<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class FftTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $max_sec = 20; //取得測定時間上限（計算時間比例）

        $code = '5_MV002_SN210202-16_45cmH2O_30LPM_210529';
        $fn = '../analyze/sound_file/'.$code.'.wav';
        $wave_data = WaveUtil::extractWaveData($fn,$max_sec);
        $y_max = max(max($wave_data->func[0]),-min($wave_data->func[0]));
        echo $y_max;
        $func = array_map(function ($x) use ($y_max) {
            return $x / $y_max;
        }, $wave_data->func[0]);

        $sr = $wave_data->sampling_rate;
        $dt = 1 / $sr;
        $n = 2; //呼吸音取得サンプル数指定

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
        };
        $hann = function ($index, $value) use ($win_length) {
            return $value * (0.5 - (0.5 * cos(2 * M_PI * $index / ($win_length - 1))));
        };
        $hamming = function ($index, $value) use ($win_length) {
            return $value * (0.54 - (0.46 * cos(2 * M_PI * $index / ($win_length - 1))));
        };

        $fft = new Fft($win_length);

        $pulse_count = 0;
        $pulse_times = [];
        $pulses = [];
        $cool_time = 100; //ピーク検出後に飛ばすインデックス時間換算0.1秒
        $ma = []; //移動平均配列(5)
        $exhs = []; //呼気：小さめのピーク(シュー)
        $inhs = []; //吸気：大きめのピーク(カチッ)
        $hear_flg = 1; //1のときヒア状態。ピーク検出で0に

        $slice_starts = [
            70910,
            122390,
            182838
        ];

        $status = 'click';//カチッ音：click、呼気：in_initial(序盤),in_middle(中盤),in_terminal(終盤)

        foreach ($slice_starts as $slice_start) {
            $sliced_func = array_slice($func, $slice_start, $win_length);
            $wined_func = array_map($blackman, array_keys($sliced_func), $sliced_func);

            $fftfunc = $fft->fft($wined_func);
            $fftabs = $fft->getAbsFFT($fftfunc);

            //書き出し
            $stream = fopen('../analyze/'.$slice_start. $code. $slice_start . '.csv', 'w');
            foreach ($fftabs as $key => $value) {
                fputcsv($stream, [$key, $value]);
                if ($key === 63) break;
            }
            fclose($stream);
        }
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
        $thresholds = [
            //吸気はじめのカチッ
            'in' => [
                [
                    'index_min' => 4,
                    'index_max' => 6,
                    'freq_min' => 1550,
                    'freq_max' => 2100,
                    'amp' => 0.04
                ],
                [
                    'index_min' => 7,
                    'index_max' => 8,
                    'freq_min' => 2550,
                    'freq_max' => 2950,
                    'amp' => 0.03
                ],
                [
                    'index_min' => 13,
                    'index_max' => 14,
                    'freq_min' => 4650,
                    'freq_max' => 5000,
                    'amp' => 0.03
                ],
                [
                    'index_min' => 23,
                    'index_max' => 25,
                    'freq_min' => 7900,
                    'freq_max' => 8800,
                    'amp' => 0.03
                ]
            ],
            //吸気のシュー
            'ex' => [
                [
                    'index_min' => 10,
                    'index_max' => 13,
                    'freq_min' => 1700,
                    'freq_max' => 2250,
                    'amp' => 0.020
                ],
                [
                    'index_min' => 36,
                    'index_max' => 42,
                    'freq_min' => 6200,
                    'freq_max' => 7250,
                    'amp' => 0.005
                ]
            ]
        ];

        $threshold = $mode ? $thresholds['ex'] : $thresholds['in'];

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
}
