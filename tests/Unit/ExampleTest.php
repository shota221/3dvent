<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $fn = 'public/sample.wav';
        $wave_data = WaveUtil::extractWaveData($fn);
        $y_max = max($wave_data->func[0]);
        $func = array_map(function ($x) use ($y_max) {
            return $x / $y_max;
        }, $wave_data->func[0]);

        $sr = $wave_data->sampling_rate;
        $dt = 1 / $sr;
        $n = 2;//呼吸音取得サンプル数指定

        $max_sec = 15;//取得測定時間上限（計算時間比例）

        $wave_length = $wave_data->length;
        $step = 128; //second/step=$step*$dt
        $win_length = 256;//窓幅を大きくすると周波数分解能があがりダイナミックレンジがさがる


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
        $cool_time = 100;//ピーク検出後に飛ばすインデックス
        $ma = []; //移動平均配列(5)
        $exhs = []; //呼気：小さめのピーク
        $inhs = []; //吸気：大きめのピーク
        $hear_flg = 1;//1のときヒア状態。ピーク検出で0に
        for ($i = 0; $i * $step <= min($wave_length - $win_length,$sr*$max_sec); $i++) {
            $sliced_func = array_slice($func, $i*$step, $win_length);
            $wined_func = array_map($blackman, array_keys($sliced_func), $sliced_func);

            $fftfunc = $fft->fft($wined_func);
            $fftabs = $fft->getAbsFFT($fftfunc);


            $sum = 0;
            for ($j = $i_a; $j <= $i_b; $j++) {
                $sum += $fftabs[$j];
            }

            $sum_log = log10($sum);

            // echo $sum_log."\n";

            if($sum_log >= -1.5 && $hear_flg === 1) {
                $pulse_times[] = round($i*$step*$dt,2);
                $pulses[] = $sum_log;
                $pulse_count++;
                $i+=$cool_time;//クールタイム分とばす
            }
            
            //パルスが2*$n+1カウントされた時点で終了
            if($pulse_count===2*$n+1) break;

            $ma[$i % 5] = $sum;
            if ($i < 5) continue;            
           if($hear_flg===0 && log10(array_sum($ma))<=-1) $hear_flg=1; //移動平均線が-1を下回ったときにピーク受け入れ状態になる
        
        } 


        foreach($pulse_times as $pulse){
            echo $pulse."\n";
        }

        if(count($pulses)<2*$n+1) {
            //音が小さすぎるor測定時間が短すぎる
            return false;
        }

        if($pulses[0]<$pulses[1]){
            //呼気スタート音のほうが小さい
            for($i=0;$i<$n;$i++){
                if($pulses[2*$i+1]<$pulses[2*$i]||$pulses[2*$i+1]<$pulses[2*$i+2]) {
                    //パルスの大小が不規則->クリック音が正しく取得されていない＝雑音が大きい
                    return false;
                }
                $exhs[] = $pulse_times[2*$i+1]-$pulse_times[2*$i];
                $inhs[] = $pulse_times[2*$i+2]-$pulse_times[2*$i+1];
            }
        } else {
            for($i=0;$i<$n;$i++){
                if($pulses[2*$i+1]<$pulses[2*$i]||$pulses[2*$i+1]<$pulses[2*$i+2]) {
                    //パルスの大小が不規則->クリック音が正しく取得されていない＝雑音が大きい
                    return false;
                }
                $inhs[] = $pulse_times[2*$i+1]-$pulse_times[2*$i];
                $exhs[] = $pulse_times[2*$i+2]-$pulse_times[2*$i+1];
            }
        }


        echo 'in:'.array_sum($inhs)/$n.'ex:'.array_sum($exhs)/$n;

        $this->assertTrue(true);
    }
}
