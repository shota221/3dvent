<?php 

namespace App\Services\Support\Converter;

use App\Models\User;
use App\Http\Response as Response;

class UserResponseConverter
{
    public static function convertToUserAuthResult(string $redirect_to)
    {
        $res = new Response\UserAuthResult;

        $res->redirect_to = $redirect_to;

        return $res;
    }
}