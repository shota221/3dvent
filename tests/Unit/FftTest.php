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

        $code = '5_MV002_SN210202-16_8cmH2O_5LPM_210529';

        $slice_starts = [
            67441,
            164305,
            261929,
        ];

        $status = 'click_middle';//カチッ音：click、呼気：in_initial(序盤),in_middle(中盤),in_terminal(終盤)

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



        echo count($func);


        foreach ($slice_starts as $slice_start) {
            $sliced_func = array_slice($func, $slice_start, $win_length);
            $wined_func = array_map($blackman, array_keys($sliced_func), $sliced_func);

            $fftfunc = $fft->fft($wined_func);
            $fftabs = $fft->getAbsFFT($fftfunc);

            

            //書き出し
            $stream = fopen('../analyze/'.$slice_start. $code. $status . '.csv', 'w');
            foreach ($fftabs as $key => $value) {
                fputcsv($stream, [$key, StatisticUtil::standardScore($fftabs,$value)]);
                if ($key === 63) break;
            }
            fclose($stream);
        }
    }

    public static function collectFft($code,$func,$slice_starts)
    {   
        $win_length = 128; //窓幅を大きくすると周波数分解能があがりダイナミックレンジがさがる

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


        foreach ($slice_starts as $slice_start) {
            $sliced_func = array_slice($func, $slice_start, $win_length);
            $wined_func = array_map($blackman, array_keys($sliced_func), $sliced_func);

            $fftfunc = $fft->fft($wined_func);
            $fftabs = $fft->getAbsFFT($fftfunc);

            

            //書き出し
            $stream = fopen('../analyze/'.$slice_start.'-'. $code. '.csv', 'w');
            foreach ($fftabs as $key => $value) {
                fputcsv($stream, [$key, StatisticUtil::standardScore($fftabs,$value)]);
                if ($key === 63) break;
            }
            fclose($stream);
        }
    }
}