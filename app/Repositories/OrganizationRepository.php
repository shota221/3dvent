<?php

namespace App\Repositories;

use App\Models\Organization;

class OrganizationRepository
{
    private static function query()
    {
        return Organization::query();
    }

    public static function findOneById(int $organization_id)
    {
        return static::query()->where('id',$organization_id)->first();
    }

    public static function findOneByCode(string $organization_code)
    {
        return static::query()->where('code',$organization_code)->first();
    }
}