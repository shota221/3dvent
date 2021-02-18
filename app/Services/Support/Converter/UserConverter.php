<?php 

namespace App\Services\Support\Converter;

use App\Http\Response as Response;

class UserConverter
{
    public static function convertToUserTokenResult($login = true)
    {
        $res = new Response\Api\UserTokenResult;

        $res->user_id = 3;

        $res->api_token = $login ===  true ? "###user_token###" : null;

        return $res;
    }
}