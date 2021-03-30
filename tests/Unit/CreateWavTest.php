<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class CreateWavTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $json = file_get_contents('public/1-5_IMG_2070.json');

        WaveUtil::createWavFile($json);

        $this->assertTrue(true);
    }
}