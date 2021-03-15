<?php

namespace App\Services\Support;

/**
 * wavファイル操作UTILクラス
 */
class WaveUtil
{
    //デフォルトをステレオ音声と想定
    public static function extractWaveData($file_name)
    {
        //ファイルハンドラー
        $fh = fopen($file_name, 'r');

        $wave_data = new WaveData;

        $type =  fread($fh, 4);
        if ($type !== 'RIFF') {
            return false;
        }

        $wave_data->size = current(unpack('V', fread($fh, 4)));

        $format = fread($fh, 4);
        if ($format !== 'WAVE') {
            return false;
        }

        fseek($fh, 8, SEEK_CUR);

        $wave_data->format = current(unpack('v', fread($fh, 2)))?:16;
        $wave_data->channel_size = current(unpack('v', fread($fh, 2)))?:2;
        $wave_data->sampling_rate = current(unpack('V', fread($fh, 4)))?:44100;
        $wave_data->byte_per_second = current(unpack('V', fread($fh, 4)));
        $wave_data->block_size = current(unpack('v', fread($fh, 2)))?:4;
        $wave_data->bit_per_sample = current(unpack('v', fread($fh, 2)))?:16;

        $position = ftell($fh);
        while (true) {
            if (fread($fh, 4) === 'data') break;
            $position++;
            fseek($fh, $position);
        }
        //このとき$positionは'data'の頭を指している

        $wave_data->func_size = current(unpack('V', fread($fh, 4)));

        $wave_data->length = $wave_data->func_size / $wave_data->block_size;

        for ($i = 0; $i < $wave_data->length; $i++) {
            for ($j = 0; $j < $wave_data->channel_size; $j++) {
                $wave_data->func[$j][] = current(unpack('s', fread($fh, $wave_data->bit_per_sample/8)));
            }
        }

        return $wave_data;
    }
}
