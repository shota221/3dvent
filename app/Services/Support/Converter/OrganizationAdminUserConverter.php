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
        int    $authority,
        int    $created_user_id,
        int    $organization_id,
        string $name,
        string $email,
        int    $org_authority_type,
        string $hashed_password,
        int    $disabled_flg)
    {
        $entity = new Models\User;
        
        $entity->authority          = $authority;
        $entity->created_user_id    = $created_user_id;
        $entity->organization_id    = $organization_id;
        $entity->name               = $name;
        $entity->email              = $email;
        $entity->org_authority_type = $org_authority_type;
        $entity->password           = $hashed_password;
        $entity->disabled_flg       = $disabled_flg;

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
        string $path)
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
            ['path' => $path]
        );

        return $paginator;
    }

    public static function convertToOrganizationSearchListData(\Illuminate\Database\Eloquent\Collection $entities)
    {
        $organizations = array_map(
            function($entity) {
                return self::convertToOrganizationData($entity);
            }
            ,$entities->all()
        );

        return $organizations;
    }
    
    private static function convertToOrganizationData(Models\Organization $entity)
    {
        $data = new Response\Admin\OrganizationData();

        $data->id = $entity->id;
        $data->name = $entity->name;

        return $data;
    }
}