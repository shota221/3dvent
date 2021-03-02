<?php 

namespace App\Services\Support\Converter;

use App\Http\Response as Response;

class IeConverter
{
    public static function convertToIeResult($i_avg,$e_avg,$rr) 
    {
        $res = new Response\Api\IeResult;

        $res->i_avg = $i_avg;

        $res->e_avg = $e_avg;

        $res->rr = $rr;

        return $res;
    }
}