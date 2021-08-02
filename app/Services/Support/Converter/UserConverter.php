<?php

namespace App\Services\Support\Converter;

use App\Http\Response as Response;
use App\Models\User;

class UserConverter
{
    public static function convertToEntity(
        string $name,
        int $organization_id,
        string $password,
        int $authority,
        string $email = '',
        string $status = USER::DISABLED,
        int $created_user_id = null,
        int $created_admin_user_id = null,
    ) {
        $entity = new User;
        
        $entity->name = $name;
        $entity->organization_id = $organization_id;
        $entity->password = $password;
        $entity->authority = $authority;
        $entity->email = $email;
        $entity->status = $status;
        $entity->created_user_id = $created_user_id;
        $entity->created_admin_user_id = $created_admin_user_id;

        return $entity;
    }

    public static function convertToLoginUserResult(int $id, string $api_token = null, string $user_name = null, string $organization_name = null)
    {
        $res = new Response\Api\UserResult;

        $res->user_id = $id;

        $res->api_token = $api_token;

        $res->user_name = $user_name;

        $res->organization_name = $organization_name;

        return $res;
    }

    public static function convertToLogoutUserResult(int $id = null)
    {
        $res = new Response\Api\UserResult;

        $res->user_id = $id;

        return $res;
    }

    public static function convertToUserResult(string $user_name, string $organization_name, string $email = null)
    {
        $res = new Response\Api\UserResult;

        $res->user_name = $user_name;

        $res->organization_name = $organization_name;

        $res->email = $email;

        return $res;
    }

    public static function convertToUserUpdateResult(string $user_name, string $email = null)
    {
        $res = new Response\Api\UserResult;

        $res->user_name = $user_name;

        $res->email = $email;

        return $res;
    }

    // 追加　php8から必須でないパラメータが必須パラメータより前に定義されているとエラーを吐くようになったため$update_user_idと$emailの順番を入れ替えた。$emailのデフォは空文字に変更
    public static function convertToUserUpdateEntity(
        User $entity,
        string $user_name,
        $updated_user_id,
        $email = ''
    ) {
        $entity->name = $user_name;

        $entity->updated_user_id = $updated_user_id;

        $entity->email = strval($email);

        return $entity;
    }

    public static function convertToCheckHasTokenResult($has_token)
    {
        $res = new Response\Api\UserResult;

        $res->has_token = $has_token;

        return $res;
    }
}
