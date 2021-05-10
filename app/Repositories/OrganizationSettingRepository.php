<?php

namespace App\Repositories;

use App\Models\OrganizationSetting;

class OrganizationSettingRepository
{
    private static function query()
    {
        return OrganizationSetting::query();
    }

    public static function findOneByOrganizationId(int $organization_id)
    {
        return static::query()->where('id', $organization_id)->first();
    }
}