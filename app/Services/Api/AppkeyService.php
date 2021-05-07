<?php

namespace App\Services\Api;

use App\Exceptions;
use App\Http\Auth\AppkeyGate;
use App\Models;
use App\Http\Forms\Api as Form;
use App\Http\Response as Response;
use App\Repositories as Repos;
use App\Models\Report;
use App\Services\Support as Support;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
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
