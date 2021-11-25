<?php

namespace App\Services\Support\Converter;

use App\Http\Response as Response;
use App\Models\Room;
use App\Http\Forms\Api as Form;
use App\Services\Support\CryptUtil;

class RoomConverter
{
    public static function convertToEntity($name, $token, $appkey_id)
    {
        $entity = new Room;

        $entity->name = $name;

        $entity->token = $token;

        $entity->appkey_id = $appkey_id;

        return $entity;
    }

    public static function convertToRoomResult($uri)
    {
        $res = new Response\Api\RoomResult;

        $res->uri = $uri;

        return $res;
    }
}
