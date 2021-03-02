<?php

namespace App\Services\Api;

use App\Exceptions;
use App\Models;
use App\Http\Forms\Api as Form;
use App\Http\Response as Response;
use App\Repositories as Repos;
use App\Models\Report;
use App\Services\Support as Support;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Services\Support\Converter;

class CalcService
{
    use Support\Logic\CalculationLogic;

    public function getDefaultFlow($form)
    {
        return Converter\VentilatorConverter::convertToDefaultFlowResult();
    }

    public function getEstimatedData($form)
    {
        $estimated_peep = !is_null($form->airway_pressure) ? $this->calcEstimatedPeep(floatval($form->airway_pressure)) : null;

        if (!is_null($form->air_flow) && !is_null($form->o2_flow)) {
            if (floatval($form->air_flow) + floatval($form->o2_flow) === 0.0) {
                $fio2 = 0.0;
            } else {
                $fio2 = $this->calcFio2(floatval($form->air_flow), floatval($form->o2_flow));
            }
        } else {
            $fio2 = null;
        }
        return Converter\VentilatorConverter::convertToEstimatedDataResult($estimated_peep, $fio2);
    }

    public function getIeManual($form)
    {
        $i_e_avg = $this->calcIeAvg($form->data);

        $rr = $this->calcRr($i_e_avg['i'],$i_e_avg['e']);       

        return Converter\IeConverter::convertToIeResult($i_e_avg['i'],$i_e_avg['e'],$rr);
    }

    public function getIeSound($form)
    {
        return Converter\IeConverter::convertToIeResult(1.280,0.971,26.65);
    }
}
