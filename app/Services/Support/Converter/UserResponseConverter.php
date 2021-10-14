<?php 

namespace App\Services\Support\Converter;

use App\Models\User;
use App\Http\Response as Response;

class UserResponseConverter
{
    public static function convertToUserProfileResult(User $entity) 
    {
        $res = new Response\UserProfileJsonResult;

        $res->name  = $entity->name;
        $res->email = $entity->email;
    
        return $res;
    }

    public static function convertToUserAuthResult(string $redirect_to)
    {
        $res = new Response\UserAuthResult;

        $res->redirect_to = $redirect_to;

        return $res;
    }

    public static function convertToPasswordResetResult()
    {
        $res = new Response\UserPasswordResetResult;

        $res->home_url = guess_route_path('home');

        return $res;
    }
}