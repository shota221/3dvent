<?php

namespace App\Services\Support\Converter;

use App\Http\Response as Response;
use App\Models\User;
use App\Services\Support\Converter;
use App\Services\Support\DateUtil;
use Illuminate\Pagination\LengthAwarePaginator;

class UserConverter
{
    public static function convertToEntity(
        int    $organization_id,
        int    $created_user_id,
        string $name,
        string $email,
        int    $org_authority_type,
        int    $authority,
        int    $disabled_flg,
        string $hashed_password
    ) {
        $entity = new User;
        
        $entity->organization_id     = $organization_id;
        $entity->created_user_id     = $created_user_id;
        $entity->name                = $name;
        $entity->email               = $email;
        $entity->org_authority_type  = $org_authority_type;
        $entity->authority           = $authority;
        $entity->disabled_flg        = $disabled_flg;
        $entity->password            = $hashed_password;

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

    public static function convertToPaginatedUserData(
        \Illuminate\Database\Eloquent\Collection $entities,
        int $total_count,
        int $item_per_page,
        string $path)
    {
        $users = array_map(
            function ($entity) {
                return self::convertToUserData($entity);
            }
            ,$entities->all()
        );

        $paginator = new LengthAwarePaginator(
            $users,
            $total_count,
            $item_per_page,
            null,
            ['path' => $path]
        );

        return $paginator;
    }

    public static function convertToUserData(User $entity)
    {
        $data = new Response\Org\UserData;

        $data->id                 = $entity->id;
        $data->name               = $entity->name;
        $data->org_authority_name = Converter\Lang\Authority::convertToOrgAuthorityName($entity->org_authority_type);
        $data->org_authority_type = $entity->org_authority_type;
        $data->email              = $entity->email;
        $data->created_at         = DateUtil::toDatetimeStr($entity->created_at);
        $data->disabled_flg       = $entity->disabled_flg;

        return $data;
    }
}
