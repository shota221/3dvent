<?php

namespace App\Services\Support\Logic;

use App\Exceptions;

use App\Models;
use App\Repositories as Repos;
use App\Http\Forms as Form;
use App\Services\Support as Support;
use App\Models\Report;
use App\Services\Support\Converter;
use App\Http\Response\Api as Response;
use Closure;

trait CalculationLogic
{
    /**
     * 理想体重(kg)の算出、小数点第一位まで
     *
     * @param float $height
     * @param integer $gender
     * @return float
     */
    public function calcIdealWeight(float $height, int $gender)
    {
        switch ($gender) {
            case 1: //男性の場合
                return round(50.0 + 0.91 * ($height - 152.4),2);
            case 2: //女性の場合
                return round(45.5 + 0.91 * ($height - 152.4),2);
        }
    }

    /**
     * 予測一回換気量(ml/回)の算出、小数点第一位まで
     *
     * @param float $ideal_weight
     * @param float $vt_per_kg
     * @return float
     */
    public function calcPredictedVt(float $ideal_weight, float $vt_per_kg)
    {
        return round($ideal_weight * $vt_per_kg);
    }

    /**
     * EstimatedPeep(cmH2O)の算出、小数点第一位まで
     * 臨床データをもとに線形近似。$a,$bは変更の可能性アリ
     *
     * @param float $airway_pressure
     * @return float
     */
    public function calcEstimatedPeep(float $airway_pressure)
    {
        $a = 2.425;
        $b = 0.195;

        return round($a + $b * $airway_pressure,1);
    }

    /**
     * FiO2(%)の算出、小数点第一位まで
     *
     * @param float $air_flow
     * @param float $o2_flow
     * @return float
     */
    public function calcFio2(float $air_flow, float $o2_flow)
    {
        $total_flow = $air_flow + $o2_flow;
        return round((($air_flow * 0.21 + $o2_flow) / $total_flow) * 100,1);
    }

    /**
     * RR(回/min)の算出、小数点第一位まで
     *
     * @param float $i_avg
     * @param float $e_avg
     * @return float
     */
    public function calcRr(float $i_avg, float $e_avg)
    {
        return 60 / ($e_avg + $i_avg);
    }

    /**
     * EstimatedVt(ml/回)の算出、小数点第一位まで
     *
     * @param float $i_avg
     * @param float $total_flow
     * @return float
     */
    public function calcEstimatedVt(float $i_avg, float $total_flow)
    {
        return ($total_flow / 60) * $i_avg * 1000;
    }

    /**
     * EstimatedMV(L/min)の算出、小数点第一位まで
     *
     * @param float $vt
     * @param float $rr
     * @return float
     */
    public function calcEstimatedMv(float $vt, float $rr)
    {
        return $vt * $rr / 1000;
    }
}
