<?php

namespace App\Repositories;

use App\Models\Room;

class RoomRepository
{
    private static function query()
    {
        return Room::query();
    }

    public static function findOneByAppkeyId($appkey_id)
    {
        return static::query()->where('appkey_id',$appkey_id)->first();
    }
}
