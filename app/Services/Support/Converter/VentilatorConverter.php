<?php 

namespace App\Services\Support\Converter;

use App\Http\Response as Response;

class VentilatorConverter
{
    public static function convertToVentilatorResult() 
    {
        $res = new Response\Api\VentilatorResult;

        $res->is_registered = true;

        $res->ventilator_id = 1;

        $res->patient_id = 1;

        $res->organization_name = 'テスト組織';

        return $res;
    }

    public static function convertToDefaultFlowResult() 
    {
        $res = new Response\Api\VentilatorValueResult;
        
        $res->air_flow =  '9.0';

        $res->o2_flow =  '3.0';

        return $res;
    }

    public static function convertToEstimatedDataResult($estimated_peep = null,$fio2 = null) 
    {
        $res = new Response\Api\VentilatorValueResult;

        $res->estimated_peep = $estimated_peep ?? null;

        $res->fio2 = $fio2 ?? null;

        return $res;
    }

    public static function convertToVentilatorRegistrationResult()
    {
        $res = new Response\Api\VentilatorResult;

        $res->ventilator_id = 1;

        $res->organization_name = 'テスト組織';

        return $res;
    }

    public static function convertToVentilatorValueRegistrationResult()
    {
        $res = new Response\Api\VentilatorValueResult;

        $res->ventilator_id = 1;

        $res->estimated_vt = '446';

        $res->estimated_mv = '6.9';

        $res->estimated_peep = '7.3';

        $res->fio2 = '47.3';

        return $res;
    }

    public static function convertToVentilatorValueResult() 
    {
        $res = new Response\Api\VentilatorValueResult;

        $res->airway_pressure = '23.6';

        $res->air_flow = '12.0';

        $res->o2_flow = '6.0';

        $res->rr = '15.9';

        $res->estimated_vt = '446';

        $res->estimated_mv = '6.9';

        $res->estimated_peep = '7.3';

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