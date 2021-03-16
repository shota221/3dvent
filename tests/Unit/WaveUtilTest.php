<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class WaveUtilTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $wave_data = WaveUtil::extractWaveData('etc/test.wav');
        echo $wave_data->sampling_rate;
    }
}