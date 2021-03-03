<?php 

namespace App\Services\Support\Converter;

use App\Http\Response as Response;

class AppkeyConverter
{
    public static function convertToAppkeyResult() 
    {
        $res = new Response\Api\AppkeyResult;

        $res->appkey = '#############################';

        return $res;
    }
}