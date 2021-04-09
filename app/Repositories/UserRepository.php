<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    private static function query()
    {
        return User::query();
    }

    public static function findOneById(int $id)
    {
        return static::query()->where('id', $id)->first();
    }

    public static function findOneByToken(string $token)
    {
        return static::query()->where(User::TOKEN_COLUMN_NAME, $token)->first();
    }

    public static function findOneByOrganizationIdAndName(int $organization_id, string $name)
    {
        return static::query()->where('organization_id',$organization_id)->where('name',$name)->first();
    }
}
