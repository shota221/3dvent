<?php

namespace App\Services\Support\Converter;

use App\Http\Response as Response;

class IeConverter
{
    public static function convertToIeResult(float $i_avg, float $e_avg, float $rr, float $ie_ratio)
    {
        $res = new Response\Api\IeResult;

        $res->i_avg    = !empty($i_avg) ? strval($i_avg) : $i_avg;
        $res->e_avg    = !empty($e_avg) ? strval($e_avg) : $e_avg;
        $res->rr       = !empty($rr) ? strval($rr) : $rr;
        $res->ie_ratio = '1：' . strval($ie_ratio);

        return $res;
    }
}
