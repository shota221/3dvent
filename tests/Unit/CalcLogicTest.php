<?php

namespace Tests\Unit;

use App\Http\Forms\Api as Form;
use App\Services\Api as Service;
use App\Services\Support\Converter;
use App\Services\Support\Logic;
use Tests\TestCase;

class CalcLogicTest extends TestCase
{
    use Logic\CalculationLogic;

    public function testCalcIdealWeight()
    {
        $res = $this->calcIdealWeight(169,1);
        var_dump($res);
        $this->assertTrue(true);
    }

    public function testCalcPredictedVt()
    {
        $res = $this->calcPredictedVt(50, 6);
        var_dump($res);
        $this->assertTrue(true);
    }

    public function testCalcEstimatedPeep()
    {
        $res = $this->calcEstimatedPeep(20);
        var_dump($res);
        $this->assertTrue(true);
    }

    public function testCalcFio2()
    {
        $res = $this->calcFio2(9.0, 3.0);
        var_dump($res);
        $this->assertTrue(true);
    }

    public function testCalcIAvg()
    {
        $res = $this->calcIAvg(1.18, 20.6);
        var_dump($res);
        $this->assertTrue(true);
    }

    public function testCalcRr()
    {
        $res = $this->calcRr(1.74, 1.18);
        var_dump($res);
        $this->assertTrue(true);
    }

    public function testCalcRrFromRespirationsPer10sec()
    {
        $res = $this->calcRrFromRespirationsPer10sec(3.3);
        var_dump($res);
        $this->assertTrue(true);
    }

    public function testCalcIeRatio()
    {
        $res = $this->calcIeRatio(1.74, 1.18);
        var_dump($res);
        $this->assertTrue(true);
    }

    public function testCalcEstimatedMv()
    {
        $res = $this->calcEstimatedMv(1.74, 20.6, 10, 10);
        var_dump($res);
        $this->assertTrue(true);
    }

    public function testCalcEstimatedVt()
    {
        $res = $this->calcEstimatedVt(4.53, 20.6);
        var_dump($res);
        $this->assertTrue(true);
    }

    public function testCalcTotalFlow()
    {
        $res = $this->calcTotalFlow(9.0, 3.0);
        var_dump($res);
        $this->assertTrue(true);
    }
}
