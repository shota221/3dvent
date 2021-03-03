<?php 

namespace App\Services\Support\Converter;

use App\Http\Response as Response;

class UserConverter
{
    public static function convertToUserResult($login = true)
    {
        $res = new Response\Api\UserResult;

        $res->user_id = 3;

        $res->api_token = $login === true ? "###user_token###" : null;

        $res->user_name = $login ===  true ? "テスト太郎" : null;

        $res->organization_name = $login ===  true ? "テスト組織" : null; 

        return $res;
    }
}