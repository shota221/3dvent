<?php

namespace App\Services\Api;

use App\Exceptions;
use App\Http\Forms\Api as Form;
use App\Models;
use App\Repositories as Repos;
use App\Services\Support\Client;
use App\Services\Support\Converter;
use App\Services\Support\CryptUtil;
use App\Services\Support\DateUtil;
use App\Services\Support\DBUtil;

class RoomService
{
    public function fetch(Models\Appkey $appkey)
    {
        $room = Repos\RoomRepository::findOneByAppkeyId($appkey->id);
        //roomsテーブルに該当appkeyのroomが登録されていなければ作成
        if (is_null($room)) {
            //ルーム名に一意性をもたせる
            $now_char = DateUtil::toDatetimeChar(DateUtil::now());
            $random_str = CryptUtil::createUniqueToken($appkey->id);
            $room_name = $now_char . $random_str;
            //ルーム作成
            $created_room = (new Client\NextcloudApiClient)->createRoom($room_name);
            $room_token = $created_room->token;
            $room = Converter\RoomConverter::convertToEntity($room_name, $room_token, $appkey->id);

            DBUtil::Transaction(
                'room登録',
                function () use ($room) {
                    $room->save();
                }
            );
        }
        $host = config('nextcloud.host');
        $path = config('nextcloud.call_path');
        $uri = $host . $path . DIRECTORY_SEPARATOR . $room->token;
        return Converter\RoomConverter::convertToRoomResult($uri);
    }
}
