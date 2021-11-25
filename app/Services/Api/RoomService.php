<?php

namespace App\Services\Api;

use App\Models;
use App\Repositories as Repos;
use App\Services\Support\Client;
use App\Services\Support\Converter;
use App\Services\Support\CryptUtil;
use App\Services\Support\DateUtil;
use App\Services\Support\DBUtil;

class RoomService
{
    public function fetchRoomUri(Models\Appkey $appkey)
    {
        $room = Repos\RoomRepository::findOneByAppkeyId($appkey->id);
        $nextcloud_client = new Client\NextcloudApiClient;

        if (is_null($room)) { //roomsテーブルに該当appkeyのroomが登録されていなければ作成
            //ルーム名に一意性をもたせる
            $room_name = $this->generateUniqueRoomName($appkey->id);
            //ルーム作成
            $created_room = $nextcloud_client->createRoom($room_name);
            $room = Converter\RoomConverter::convertToEntity($room_name, $created_room->token, $appkey->id);
            DBUtil::Transaction(
                'room登録',
                function () use ($room) {
                    $room->save();
                }
            );
        } else if (!$nextcloud_client->hasRoom($room->token)) { //該当トークンのルームがnextcloud上に存在していない場合も新たに作成
            //ルーム名に一意性をもたせる
            $room_name = $this->generateUniqueRoomName($appkey->id);
            //ルーム作成
            $created_room = $nextcloud_client->createRoom($room_name);
            $room->name = $room_name;
            $room->token = $created_room->token;

            DBUtil::Transaction(
                'room情報更新',
                function () use ($room) {
                    $room->save();
                }
            );
        }
        $host = config('nextcloud.host');
        $path = config('nextcloud.call_path');
        $uri = $host . $path . '/' . $room->token;
        return Converter\RoomConverter::convertToRoomResult($uri);
    }

    /**
     * 一意なルーム名の生成
     *
     * @param integer $appkey_id
     * @return string
     */
    private function generateUniqueRoomName(int $appkey_id)
    {
        $now_str = DateUtil::toDatetimeChar(DateUtil::now());
        $random_str = CryptUtil::createUniqueToken($appkey_id);
        return $now_str . $random_str;
    }
}
