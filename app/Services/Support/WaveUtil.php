<?php

namespace App\Services\Support;

use App\Services\Support\FileUtil;
/**
 * wavファイル操作UTILクラス
 */
class WaveUtil
{
    /**
     * 音声ファイルから情報を抽出
     *
     * @param string $file_name
     * @param integer $max_sec //抽出する秒数の最大値
     * @return void
     */
    public static function extractWaveData($file_name,$max_sec = 20)
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

        $position = ftell($fh);
        while (true) {
            if (substr(fread($fh, 4),0,3) == 'fmt') break;
            $position++;
            fseek($fh, $position);
        }

        fseek($fh, 4, SEEK_CUR);

        $wave_data->format = current(unpack('v', fread($fh, 2)));
        $wave_data->channel_size = current(unpack('v', fread($fh, 2))) ?: 1;
        $wave_data->sampling_rate = current(unpack('V', fread($fh, 4))) ?: 44100;
        $wave_data->byte_per_second = current(unpack('V', fread($fh, 4)));
        $wave_data->block_size = current(unpack('v', fread($fh, 2)))?: 2;
        $wave_data->bit_per_sample = current(unpack('v', fread($fh, 2))) ?: 16;

        $wave_data->byte_per_second = $wave_data->sampling_rate*$wave_data->block_size;

        $position = ftell($fh);
        while (true) {
            if (fread($fh, 4) === 'data') break;
            $position++;
            fseek($fh, $position);
        }
        //このとき$positionは'data'の頭を指している

        $wave_data->func_size = min(current(unpack('V', fread($fh, 4))),$wave_data->byte_per_second*$max_sec);

        $wave_data->length = $wave_data->func_size / $wave_data->block_size;

        for ($i = 0; $i < $wave_data->length; $i++) {
            for ($j = 0; $j < $wave_data->channel_size; $j++) {
                $elm = current(unpack('s', fread($fh, $wave_data->bit_per_sample/8)));
                $wave_data->func[$j][] = $elm;
            }
        }

        fclose($fh);

        return $wave_data;
    }
}