<?php 

namespace App\Services\Support\Converter;

use App\Http\Response as Response;

class UserConverter
{
    public static function convertToUserTokenResult()
    {
        $res = new Response\Api\UserTokenResult;

        $res->user_id = 3;

        $res->api_token = '##user_token##';

        return $res;
    }
}