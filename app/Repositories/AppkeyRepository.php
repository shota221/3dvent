<?php

namespace App\Repositories;

use App\Models\Appkey;

class AppkeyRepository
{
    private static function query()
    {
        return Appkey::query();
    }

    public static function existsByAppkey($appkey)
    {
        return static::query()->where('appkey', $appkey)->exists();
    }

    public static function findOneByAppkey($appkey)
    {
        return static::query()->where('appkey', $appkey)->first();
    }
}
