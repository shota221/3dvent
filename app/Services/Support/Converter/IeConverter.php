<?php 

namespace App\Services\Support\Converter;

use App\Http\Response as Response;

class IeConverter
{
    public static function convertToIeResult() 
    {
        $res = new Response\Api\IeResult;

        $res->i_avg = '2.2';

        $res->e_avg = '1.2';

        $res->rr = '16.7';

        return $res;
    }
}