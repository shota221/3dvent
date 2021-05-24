<?php

namespace App\Services\Api;

use App\Exceptions;
use App\Repositories as Repos;
use App\Repositories\PatientRepository;
use App\Services\Support as Support;
use App\Services\Support\Client\ReverseGeocodingClient;
use App\Services\Support\Converter;
use App\Services\Support\DBUtil;
use App\Services\Support\DateUtil;

class VentilatorService
{
    use Support\Logic\CalculationLogic;

    /**
     * gs1コードから呼吸器情報を取得する
     *
     * @param [type] $form
     * @return void
     */
    public function getVentilatorResult($form, $user = null)
    {
        if (!Repos\VentilatorRepository::existsByGs1Code($form->gs1_code)) {
            return Converter\VentilatorConverter::convertToVentilatorResult();
        }

        $ventilator = Repos\VentilatorRepository::findOneByGs1Code($form->gs1_code);

        //no_auth
        if (is_null($user)) {
            return  Converter\VentilatorConverter::convertToVentilatorResult($ventilator);
        }

        $v_org_id = $ventilator->organization_id;

        $u_org_id = $user->organization_id;

        return Converter\VentilatorConverter::convertToVentilatorResult($ventilator, is_null($v_org_id) || $v_org_id === $u_org_id);
    }

    /**
     * gs1コード等から呼吸器情報を登録する
     *
     * @param [type] $form
     * @param [type] $user
     * @return void
     */
    public function create($form, $user = null)
    {
        $registered_user_id = null;
        $organization_id = null;
        $city = null;

        if (!is_null($user)) {
            $registered_user_id = $user->id;
            $organization_id = $user->organization_id;
        }

        $serial_number = substr($form->gs1_code, -5);

        if (!is_null($form->latitude) && !is_null($form->longitude)) {
            $city = (new Support\Client\ReverseGeocodingApiClient)->getReverseGeocodingData($form->latitude, $form->longitude, 13)->display_name;
        }

        $entity = Converter\VentilatorConverter::convertToVentilatorEntity($form->gs1_code, $serial_number, DateUtil::toDatetimeStr(DateUtil::now()), $form->latitude, $form->longitude, $city, $organization_id, $registered_user_id);

        DBUtil::Transaction(
            '呼吸器情報登録',
            function () use ($entity) {
                $entity->save();
            }
        );

        //組織名込の情報を際取得
        $ventilator = Repos\VentilatorRepository::findOneByGs1Code($entity->gs1_code);

        return Converter\VentilatorConverter::convertToVentilatorRegistrationResult($ventilator);
    }

    public function update($form, $user)
    {
        if (!Repos\VentilatorRepository::existsById($form->id)) {
            $form->addError('id', 'validation.id_not_found');
            return false;
        }

        $ventilator = Repos\VentilatorRepository::findOneById($form->id);

        $v_org_id = $ventilator->organization_id;

        $u_org_id = $user->organization_id;

        //組織情報の整合チェック
        if (!is_null($v_org_id) && $v_org_id !== $u_org_id) {
            $form->addError('id', 'validation.organization_mismatch');
            return false;
        }

        $entity = Converter\VentilatorConverter::convertToVentilatorUpdateEntity($ventilator, $u_org_id, $form->start_using_at);

        //初回未ログイン状態で機器を読み取ったものの、患者登録を行わないまま中断。次回ログイン状態で機器を読み取った場合の処理
        if (is_null($entity->patient_id)) {
            DBUtil::Transaction(
                '呼吸器情報更新',
                function () use ($entity) {
                    $entity->save();
                }
            );

            return Converter\VentilatorConverter::convertToVentilatorUpdateResult($entity);
        }

        $patient = Repos\PatientRepository::findOneById($entity->patient_id);

        //呼吸器の組織ひも付き→呼吸器に紐づく患者も組織に紐づく
        $patient->organization_id = $u_org_id;

        DBUtil::Transaction(
            '呼吸器情報更新',
            function () use ($entity, $patient) {
                $entity->save();
                $patient->save();
            }
        );

        return Converter\VentilatorConverter::convertToVentilatorUpdateResult($entity);
    }
}
