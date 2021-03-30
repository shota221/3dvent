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
        $fn = 'public/2-20_新潟病院 #42.wav';
        $wave_data = WaveUtil::extractWaveData($fn);
        $y_max = max($wave_data->func[0]);
        $func = array_map(function ($x) use ($y_max) {
            return $x / $y_max;
        }, $wave_data->func[0]);

        $sr = $wave_data->sampling_rate;
        $dt = 1 / $sr;
        $n = 2; //呼吸音取得サンプル数指定

        $max_sec = 15; //取得測定時間上限（計算時間比例）

        $wave_length = $wave_data->length;
        $step = 128; //second/step=$step*$dt
        $win_length = 128; //窓幅を大きくすると周波数分解能があがりダイナミックレンジがさがる


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
            50410,
            123250,
            195650,
            268715,
            341330            
        ];
        foreach ($slice_starts as $slice_start) {
            $sliced_func = array_slice($func, $slice_start, $win_length);
            $wined_func = array_map($blackman, array_keys($sliced_func), $sliced_func);

            $fftfunc = $fft->fft($wined_func);
            $fftabs = $fft->getAbsFFT($fftfunc);

            // echo 'case' . $slice_start . "\n";
            // foreach ($this->findPeakIndex(array_slice($fftabs, 0, 128),true) as $num) {
            //     echo $num . "\n";
            // }
            //書き出し
            $stream = fopen('../analyze/2-20-128click' . $slice_start . '.csv', 'w');
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
