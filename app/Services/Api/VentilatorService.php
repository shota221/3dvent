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

class VentilatorService
{
    public function getVentilatorResult($form)
    {
        if (!Repos\VentilatorRepository::existsByGs1Code($form->gs1_code)) {
            return Converter\VentilatorConverter::convertToVentilatorResult();
        }
        $ventilator = Repos\VentilatorRepository::findOneByGs1Code($form->gs1_code);

        return Converter\VentilatorConverter::convertToVentilatorResult($ventilator);
    }

    public function create($form,$user_token)
    {
        if (!is_null($user_token)) {
            //TODO Auth:user()からの取得
            $form->registered_user_id = 3;
            $form->organization_id = 1;
        }

        $entity = Converter\VentilatorConverter::convertToVentilatorEntity($form);

        DBUtil::Transaction(
            '患者情報登録',
            function () use ($entity) {
                $entity->save();
            }
        );

        //組織名込の情報を際取得
        $ventilator = Repos\VentilatorRepository::findOneByGs1Code($entity->gs1_code);

        return Converter\VentilatorConverter::convertToVentilatorRegistrationResult($ventilator);
    }

    public function getVentilatorValue()
    {
        return Converter\VentilatorConverter::convertToVentilatorValueResult();
    }

    public function createVentilatorValue()
    {
        return Converter\VentilatorConverter::convertToVentilatorValueRegistrationResult();
    }

    public function updateVentilatorValue()
    {
        return Converter\VentilatorConverter::convertToVentilatorValueUpdateResult();
    }
}