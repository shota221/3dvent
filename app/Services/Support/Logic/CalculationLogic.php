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
use Exception;

trait CalculationLogic
{
    /**
     * 理想体重(kg)の算出。
     * heightの入力値の小数部桁数を1と想定すると3桁分取得可能
     *
     * @param float $height
     * @param integer $gender
     * @return float
     */
    public function calcIdealWeight(float $height, int $gender)
    {
        $parameter = [];

        switch ($gender) {
            case 1: //男性の場合
                $parameter = config('calc.parameter.ideal_weight.male');
                break;
            case 2: //女性の場合
                $parameter = config('calc.parameter.ideal_weight.female');
                break;
            default: //それ以外の場合、例外を返す
                throw new Exceptions\CalculationLogicException('性別指定が不正です。');
        }

        return $this->roundOff($parameter['a'] + $parameter['b'] * ($height - $parameter['c']), 'ideal_weight');
    }

    /**
     * 予測一回換気量(ml/回)の算出
     * 予測一回換気量は実際の体重ではなく、理想体重で計算を行う。
     * 
     *
     * @param float $ideal_weight
     * @param float $vt_per_kg
     * @return float
     */
    public function calcPredictedVt(float $ideal_weight, float $vt_per_kg)
    {
        return $this->roundOff($ideal_weight * $vt_per_kg, 'predicted_vt');
    }

    /**
     * EstimatedPeep(cmH2O)の算出
     * 臨床データをもとに線形近似。$a,$bは変更の可能性アリ
     *
     * @param float $airway_pressure
     * @return float
     */
    public function calcEstimatedPeep(float $airway_pressure)
    {
        $parameter = config('calc.parameter.estimated_peep');

        return $this->roundOff($parameter['a'] * $airway_pressure + $parameter['b'], 'estimated_peep');
    }

    /**
     * FiO2(%)の算出
     *
     * @param float $air_flow
     * @param float $o2_flow
     * @return float
     */
    public function calcFio2(float $air_flow, float $o2_flow)
    {
        $total_flow = $this->calcTotalFlow($air_flow, $o2_flow);
        //ゼロ除算を除外
        if ($total_flow == 0.0) {
            throw new Exceptions\CalculationLogicException('入力値が不正です。');
        }
        $net_o2_flow = $air_flow * config('calc.default.o2_concentration') + $o2_flow;
        return $this->roundOff(($net_o2_flow / $total_flow) * 100, 'fio2');
    }


    /**
     * EAvg,RrからIAvg(s)導出
     *
     * @param float $e_avg
     * @param float $rr
     * @return float
     */
    public function calcIAvg(float $e_avg, float $rr)
    {
        //ゼロ除算を除外
        if ($rr == 0.0) {
            throw new Exceptions\CalculationLogicException('入力値が不正です。');
        }
        return $this->roundOff(60 / $rr - $e_avg, 'i_avg');
    }

    /**
     * IEからRR(回/min)の算出
     *
     * @param float $i_avg
     * @param float $e_avg
     * @return float
     */
    public function calcRr(float $i_avg, float $e_avg)
    {
        $ie_avg_sum = $e_avg + $i_avg;
        //ゼロ除算を除外
        if ($ie_avg_sum == 0.0) {
            throw new Exceptions\CalculationLogicException('入力値が不正です。');
        }
        return $this->roundOff(60 / $ie_avg_sum, 'rr');
    }

    /**
     * respirations_per_10sec_avgからRR(回/min)の算出
     *
     * @param float $respirations_per_10sec
     * @return float
     */
    public function calcRrFromRespirationsPer10sec(float $respirations_per_10sec)
    {
        return $this->roundOff($respirations_per_10sec * 6, 'rr');
    }

    public function calcIeRatio(float $i, float $e)
    {
        //ゼロ除算を除外
        if ($i == 0.0) {
            throw new Exceptions\CalculationLogicException('入力値が不正です。');
        }
        return $this->roundOff($e / $i, 'ie_ratio');
    }

    /**
     * EstimatedMV(L/min)の算出
     *
     * @param float $i_avg
     * @param float $rr
     * @param float $total_flow
     * @param float $airway_pressure
     * @return float
     */
    public function calcEstimatedMv(float $i_avg, float $rr, float $total_flow, float $airway_pressure)
    {
        $parameter = config('calc.parameter.estimated_mv');
        return $this->roundOff(($i_avg * $rr / 60) * ($total_flow - $airway_pressure * $parameter['a']) + $parameter['b'], 'estimated_mv');
    }

    /**
     * EstimatedVt(ml/回)の算出
     *
     * @param float $mv
     * @param float $rr
     * @return float
     */
    public function calcEstimatedVt(float $i_avg, float $rr, float $total_flow, float $airway_pressure)
    {
        //ゼロ除算を除外
        if ($rr == 0.0) {
            throw new Exceptions\CalculationLogicException('入力値が不正です。');
        }
        $parameter = config('calc.parameter.estimated_mv');
        return $this->roundOff((($i_avg * $rr / 60) * ($total_flow - $airway_pressure * $parameter['a']) + $parameter['b']) * 1000 / $rr, 'estimated_vt');
    }

    /**
     * 供給気量の(L/min)の算出
     *
     * @param float $air_flow
     * @param float $o2_flow
     * @return float
     */
    public function calcTotalFlow(float $air_flow, float $o2_flow)
    {
        return $air_flow + $o2_flow;
    }

    /**
     *
     * @param [type] $raw_value
     * @param string|null $variable_type 変数の種別
     * @return float
     */
    public function roundOff($raw_value = null, string $variable_type = null)
    {
        try {
            $rounding_precision = config('calc.default.rounding_precision');

            if (is_null($raw_value)) {
                return null;
            }

            if (!is_null($variable_type)) {
                //種別の指定があればconfigから該当の丸め桁数を取得。該当なしの場合はデフォルト値を返す
                $rounding_precision = config('calc.rounding_precision')[$variable_type] ?? $rounding_precision;
            }

            if (!is_numeric($raw_value)) {
                throw new Exceptions\LogicException('不適切な入力');
            }
            $float_value = floatval($raw_value);
            return round($float_value, $rounding_precision);
        } catch (Exceptions\LogicException $e) {
            throw new Exceptions\CalculationLogicException('四捨五入に失敗しました。');
        }
    }
}
