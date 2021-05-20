<?php

namespace App\Services\Api;

use App\Exceptions;
use App\Services\Support\Converter;
use App\Services\Support\CryptUtil;
use App\Services\Support\DBUtil;

class AppkeyService
{
    public function create($form)
    {
        $appkey = CryptUtil::createUniqueToken($form->idfv);

        $entity = Converter\AppkeyConverter::convertToEntity($form->idfv,$appkey);

        DBUtil::Transaction(
            'アプリキー登録',
            function () use ($entity) {
                $entity->save();
            }
        );

        return Converter\AppkeyConverter::convertToAppkeyResult($entity->appkey);
    }
}
