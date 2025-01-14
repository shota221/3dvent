<?php 

namespace App\Services\Support\Converter;

use App\Http\Response as Response;
use App\Models\Appkey;
use App\Http\Forms\Api as Form;
use App\Services\Support\CryptUtil;

class AppkeyConverter
{
    public static function convertToEntity($idfv,$appkey)
    {
        $entity = new Appkey;
        
        $entity->appkey = $appkey;

        $entity->idfv = $idfv;

        return $entity;
    }

    public static function convertToAppkeyResult($appkey) 
    {
        $res = new Response\Api\AppkeyResult;

        $res->appkey = $appkey;

        return $res;
    }
}