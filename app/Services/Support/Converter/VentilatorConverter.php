<?php 

namespace App\Services\Support\Converter;

use App\Http\Response as Response;

class VentilatorConverter
{
    public static function convertToVentilatorResult() 
    {
        $res = new Response\Api\VentilatorResult;

        $res->ventilator_id = 1;

        $res->is_registered = true;

        return $res;
    }

    public static function convertToDefaultFlowResult() 
    {
        $res = new Response\Api\VentilatorValueResult;
        
        $res->flow_air =  '12.0';

        $res->flow_o2 =  '6.0';

        return $res;
    }

    public static function convertToEstimatedDataResult() 
    {
        $res = new Response\Api\VentilatorValueResult;

        $res->peep = '7.3';

        $res->fio2 = '47.3';

        return $res;
    }

    public static function convertToVentilatorValueRegistrationResult()
    {
        $res = new Response\Api\VentilatorValueResult;

        $res->ventilator_id = 1;

        $res->vt = '446';

        $res->mv = '6.9';

        $res->peep = '7.3';

        $res->fio2 = '47.3';

        return $res;
    }

    public static function convertToVentilatorValueResult() 
    {
        $res = new Response\Api\VentilatorValueResult;

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

    public static function convertToVentilatorValueUpdateResult()
    {
        $res = new Response\Api\VentilatorValueResult;

        $res->fixed_flg = true;

        return $res;
    }
}