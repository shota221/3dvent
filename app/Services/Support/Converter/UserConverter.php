<?php 

namespace App\Services\Support\Converter;

use App\Http\Response as Response;

class UserConverter
{
    public static function convertToUserResult(int $id,string $api_token = null,string $user_name = null,string $organization_name = null)
    {
        $res = new Response\Api\UserResult;

        $res->user_id = $id;

        $res->api_token = $api_token;

        $res->user_name = $user_name;

        $res->organization_name = $organization_name;

        return $res;
    }
}