<?php

namespace App\Services\Api;

use App\Exceptions;
use App\Models;
use App\Http\Forms\Api as Form;
use App\Http\Response as Response;
use App\Repositories as Repos;
use App\Models\Report;
use App\Services\Support as Support;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Services\Support\Converter;
use App\Services\Support\DBUtil;

class AppkeyService
{
    public function create($form)
    {
        //idfvは重複し得るため、カウント値と合わせてハッシュ化
        $form->appkey = hash('sha256', $form->idfv . Repos\AppkeyRepository::countByIdfv($form->idfv));

        $entity = Converter\AppkeyConverter::convertToEntity($form);

        DBUtil::Transaction(
            'アプリキー登録',
            function () use ($entity) {
                $entity->save();
            }
        );

        return Converter\AppkeyConverter::convertToAppkeyResult($entity);
    }
}
