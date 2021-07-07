<?php 

namespace App\Services\Support\Converter;

use App\Http\Response as Response;

class IeConverter
{
    public static function convertToIeResult($i_avg,$e_avg,$rr) 
    {
        $res = new Response\Api\IeResult;

        $round_at = config('calc.default.number_of_decimal_places');

        $res->i_avg = !empty($i_avg) ? strval(round($i_avg,$round_at)) : $i_avg;

        $res->e_avg = !empty($e_avg) ? strval(round($e_avg,$round_at)) : $e_avg;

        $res->rr = !empty($rr) ? strval(round($rr,$round_at)) : $rr;

        $res->ie_ratio = '1：'.strval(round($e_avg/$i_avg,$round_at));

        return $res;
    }
}