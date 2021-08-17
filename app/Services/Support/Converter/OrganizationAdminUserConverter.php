<?php

namespace App\Services\Support\Converter;

use App\Http\Forms\Admin as Form;
use App\Http\Response as Response;
use App\Models;
use App\Services\Support\DateUtil;
use Illuminate\Pagination\LengthAwarePaginator;

class OrganizationAdminUserConverter
{
    public static function convertToEntity(
        int $authority,
        int $created_user_id,
        int $organization_id,
        string $name,
        string $email,
        string $hashed_password,
        int $disabled_flg)
    {
        $entity = new Models\User;
        
        $entity->authority = $authority;
        $entity->created_user_id = $created_user_id;
        $entity->organization_id = $organization_id;
        $entity->name = $name;
        $entity->email = $email;
        $entity->password = $hashed_password;
        $entity->disabled_flg = $disabled_flg;

        return $entity;
    }

    public static function convertToUpdateEntity(
        Models\User $entity,
        int $updated_user_id,
        string $name,
        string $email,
        int $disabled_flg,
        string $hashed_password)
    {
        $entity->updated_user_id = $updated_user_id;
        $entity->name = $name;
        $entity->email = $email;
        $entity->disabled_flg = $disabled_flg;
        $entity->password = $hashed_password;

        return $entity;
    }

    public static function convertToOrganizationAdminUserData(Models\User $entity)
    {
        $data = new Response\Admin\OrganizationAdminUserData;

        $data->id = $entity->id;
        $data->name = $entity->name;
        $data->email = $entity->email;
        $data->code = $entity->code;
        $data->organization_name = $entity->organization_name;
        $data->created_at = DateUtil::toDatetimeStr($entity->created_at);
        $data->disabled_flg = $entity->disabled_flg;

        return $data;
    }

    public static function convertToPaginatedOrganizationAdminUserData(
        \Illuminate\Database\Eloquent\Collection $entities,
        int $total_count,
        int $item_per_page,
        string $base_url)
    {
        $organization_admin_users = array_map(
            function($entity) {
                return self::convertToOrganizationAdminUserData($entity);
            }
            , $entities->all()
        );

        $paginator = new LengthAwarePaginator(
            $organization_admin_users,
            $total_count,
            $item_per_page,
            null,
            ['path' => $base_url]
        );

        return $paginator;
    }
}