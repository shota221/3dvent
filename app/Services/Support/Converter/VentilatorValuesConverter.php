<?php 

namespace App\Services\Support\Converter;

use App\Http\Response as Response;

class VentilatorValuesConverter
{
    public static function convertToDefaultFlowResult() 
    {
        $res = new Response\Api\VentilatorValuesResult;
        
        $res->flow_air =  '12.0';

        $res->flow_o2 =  '6.0';

        return $res;
    }

    public static function convertToEstimatedDataResult() 
    {
        $res = new Response\Api\VentilatorValuesResult;

        $res->peep = '7.3';

        $res->fio2 = '47.3';

        return $res;
    }

    public static function convertToVentilatorValueRegistrationResult()
    {
        $res = new Response\Api\VentilatorValuesResult;

        $res->ventilator_id = 1;

        $res->vt = '446';

        $res->mv = '6.9';

        $res->peep = '7.3';

        $res->fio2 = '47.3';

        return $res;
    }

    public static function convertToVentilatorValuesResult() 
    {
        $res = new Response\Api\VentilatorValuesResult;

        $res->patient_id = 1;

        $res->airway_pressure = '23.6';

        $res->flow_air = '12.0';

        $res->flow_o2 = '6.0';

        $res->rr = '15.9';

        $res->spo2 = '96.5';

        $res->vt = '446';

        $res->mv = '6.9';

        $res->peep = '7.3';

        $res->fio2 = '47.3';

        return $res;
    }
}