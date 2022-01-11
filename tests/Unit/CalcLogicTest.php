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
        $res = $this->calcIdealWeight(169, 3);
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
        $res = $this->calcFio2(0.0, 0.0);
        var_dump($res);
        $this->assertTrue(true);
    }

    public function testCalcIAvg()
    {
        $res = $this->calcIAvg(1.18, 0.0);
        var_dump($res);
        $this->assertTrue(true);
    }

    public function testCalcRr()
    {
        $res = $this->calcRr(0.0, 0.0);
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
        $res = $this->calcIeRatio(0.0, 1.18);
        var_dump($res);
        $this->assertTrue(true);
    }

    public function testCalcEstimatedMv()
    {
        $res = $this->calcEstimatedMv(1.736, 20.61, 10.0, 10.0);
        var_dump($res);
        $this->assertTrue(true);
    }

    public function testCalcEstimatedVt()
    {
        $res = $this->calcEstimatedVt(1.736, 0.0, 10.0, 10.0);
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
