<?php

namespace Tests\Unit;

/**
 * wavファイル用DTO
 */
class WaveData
{
    public $size;

    public $format;//1：リニアPCM

    public $channel_size;

    public $sampling_rate;
    
    public $byte_per_second;

    public $block_size;

    public $bit_per_sample;

    public $func_size;//func部分のバイト数

    public $func;//y値配列、モノラルの場合は$func[0]のみ

    public $length;//ブロック数=$data[0]の要素数
}
