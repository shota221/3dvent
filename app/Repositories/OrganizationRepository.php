<?php

namespace App\Repositories;

use App\Models\Organization;

class OrganizationRepository
{
    private static function query()
    {
        return Organization::query();
    }

    public static function count($query = null)
    {
        return !is_null($query) ? $query->count() : static::query()->count();
    }

    public static function existsByCode(string $code)
    {
        return static::query()->where('code',$code)->exists();
    }

    public static function existsByRepresentativeEmail(string $representative_email)
    {
        return static::query()->where('representative_email',$representative_email)->exists();
    }


    public static function findOneById(int $id)
    {
        return static::query()->where('id', $id)->first();
    }

    public static function findOneByCode(string $code)
    {
        return static::query()->where('code', $code)->first();
    }
}