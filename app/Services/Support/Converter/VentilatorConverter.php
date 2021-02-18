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
}