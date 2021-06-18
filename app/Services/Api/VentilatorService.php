<?php

namespace App\Services\Api;

use App\Exceptions;
use App\Http\Forms\Api\VentilatorShowForm;
use App\Repositories as Repos;
use App\Repositories\PatientRepository;
use App\Services\Support as Support;
use App\Services\Support\Client\ReverseGeocodingClient;
use App\Services\Support\Converter;
use App\Services\Support\DBUtil;
use App\Services\Support\DateUtil;
use App\Services\Support\Logic as Logic;
use App\Services\Support\OrganizationCheckUtil;
use Illuminate\Validation\Rules\Exists;

class VentilatorService
{
    use Logic\CalculationLogic;
    

    /**
     * gs1コードから呼吸器情報を取得する
     *
     * @param [type] $form
     * @return void
     */
    public function getVentilatorResult(VentilatorShowForm $form, $user = null)
    {
        //未登録の場合
        $exists = Repos\VentilatorRepository::existsByGs1Code($form->gs1_code);
        if (!$exists) {
            return Converter\VentilatorConverter::convertToVentilatorResult();
        }

        $ventilator = Repos\VentilatorRepository::findOneByGs1Code($form->gs1_code);

        //no_authの場合
        $is_no_auth = is_null($user);
        if ($is_no_auth) {
            return  Converter\VentilatorConverter::convertToVentilatorResult($ventilator);
        }

        $is_match_organization_id = OrganizationCheckUtil::checkUserAgainstVentilator($user,$ventilator->id);

        if(!$is_match_organization_id){
            $form->addError('gs1_code','validation.organization_mismatch');
            throw new Exceptions\InvalidFormException($form);
        }
        
        return Converter\VentilatorConverter::convertToVentilatorResult($ventilator);
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
            throw new HttpNotFoundException();
        }

        $ventilator = Repos\VentilatorRepository::findOneById($form->id);

        $v_org_id = $ventilator->organization_id;

        $u_org_id = $user->organization_id;

        $is_match_organization_id = !is_null($v_org_id) && $v_org_id !== $u_org_id;

        // 組織情報の整合チェック
        if ($is_match_organization_id) {
            $form->addError('id', 'validation.organization_mismatch');
            return false;
        }

        $entity = Converter\VentilatorConverter::convertToVentilatorUpdateEntity($ventilator, $u_org_id, $form->start_using_at);

        $patient = null;

        $isRegisteredPatient = !is_null($entity->patient_id);

        if ($isRegisteredPatient) {
            $patient = Repos\PatientRepository::findOneById($entity->patient_id);

            // 現在の患者コード
            $current_patient_code = $patient->patient_code;

            // 現在の患者組織ID
            $current_patient_organization_id = $patient->organization_id;

            // 新患者組織ID　呼吸器の紐づき->呼吸器に紐づく患者も組織に紐づく 
            $new_patient_organization_id = $u_org_id;

            if ($current_patient_organization_id !== $new_patient_organization_id) {
                // この場合はcurrent_patient_organization_idはNULLの時に限る

                if (!is_null($current_patient_code)) {
                    $exists_new_organization_patient = Repos\PatientRepository::existsByPatientCodeAndOrganizationId(
                        $current_patient_code,
                        $new_patient_organization_id
                        );

                    if ($exists_new_organization_patient) {
                        $form->addError('patient_code', 'validation.duplicated_patient_code');
                        return false; 
                    }
                }

                // 組織セット 
                $patient->organization_id = $new_patient_organization_id;
            }
        } 

        DBUtil::Transaction(
            '呼吸器情報更新',
            function () use ($entity, $patient) {
                $entity->save();
                if (!is_null($patient)) $patient->save();
            }
        );

        return Converter\VentilatorConverter::convertToVentilatorUpdateResult($entity);

    }
}
