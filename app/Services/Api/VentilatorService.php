<?php

namespace App\Services\Api;

use App\Exceptions;
use App\Http\Auth;
use App\Http\Forms\Api as Form;
use App\Models;
use App\Repositories as Repos;
use App\Services\Support as Support;
use App\Services\Support\Converter;
use App\Services\Support\DBUtil;
use App\Services\Support\DateUtil;
use App\Services\Support\Gs1Util;
use App\Services\Support\Logic as Logic;
use App\Services\Support\OrganizationCheckUtil;

class VentilatorService
{
    use Logic\CalculationLogic;


    /**
     * gs1コードから呼吸器情報を取得する
     *
     * @param [type] $form
     * @return void
     */
    public function getVentilatorResult(Form\VentilatorShowForm $form, Models\User $user = null)
    {
        $ventilator = Repos\VentilatorRepository::findActiveOneByGs1Code($form->gs1_code);

        //アクティブなgs1Codeが登録されていない場合
        if (is_null($ventilator)) {
            return Converter\VentilatorConverter::convertToVentilatorResult();
        }

        $from = DateUtil::parseToDatetime($ventilator->start_using_at);

        $to = DateUtil::hourLater($from, config('calc.default.recommended_period_hour'));

        $is_recommended_period = DateUtil::isBetweenDateTimeToAnother(DateUtil::now(), $from, $to);

        //no_authの場合
        $is_no_auth = is_null($user);
        if ($is_no_auth) {
            return  Converter\VentilatorConverter::convertToVentilatorResult($ventilator, $is_recommended_period);
        }

        $is_match_organization_id = OrganizationCheckUtil::checkUserAgainstVentilator($user, $ventilator->id);

        if (!$is_match_organization_id) {
            $form->addError('gs1_code', 'validation.organization_mismatch');
            throw new Exceptions\InvalidFormException($form);
        }

        return Converter\VentilatorConverter::convertToVentilatorResult($ventilator, $is_recommended_period);
    }

    /**
     * gs1コード等から呼吸器情報を登録する
     *
     * @param [type] $form
     * @param [type] $user
     * @return void
     */
    public function create(Form\VentilatorCreateForm $form, Models\User $user = null)
    {
        $registered_user_id = null;
        $organization_id = null;
        $city = null;

        if (!is_null($user)) {
            $registered_user_id = $user->id;
            $organization_id = $user->organization_id;
        }

        $gs1_data = Gs1Util::extractGs1Data($form->gs1_code);

        $serial_number = $gs1_data->serial_number ?? '';

        $expiration_date = $gs1_data->expiration_date;

        if (!is_null($form->latitude) && !is_null($form->longitude)) {
            $city = (new Support\Client\ReverseGeocodingApiClient)->getReverseGeocodingData($form->latitude, $form->longitude, 13)->display_name;
        }

        $entity = Converter\VentilatorConverter::convertToVentilatorEntity($form->gs1_code, $serial_number, $expiration_date, DateUtil::toDatetimeStr(DateUtil::now()), $form->latitude, $form->longitude, $city, $organization_id, $registered_user_id);

        DBUtil::Transaction(
            '呼吸器情報登録',
            function () use ($entity) {
                $entity->save();
            }
        );

        //組織名込の情報を際取得
        $ventilator = Repos\VentilatorRepository::findActiveOneByGs1Code($entity->gs1_code);

        return Converter\VentilatorConverter::convertToVentilatorRegistrationResult($ventilator);
    }

    public function update(Form\VentilatorUpdateForm $form, Models\User $user)
    {
        $ventilator = Repos\VentilatorRepository::findActiveOneById($form->id);

        if (is_null($ventilator)) {
            $form->addError('id', 'validation.id_not_found');
            throw new Exceptions\InvalidFormException($form);
        }

        $v_org_id = $ventilator->organization_id;

        $u_org_id = $user->organization_id;

        $is_match_organization_id = !is_null($v_org_id) && $v_org_id !== $u_org_id;

        // 組織情報の整合チェック
        if ($is_match_organization_id) {
            $form->addError('id', 'validation.organization_mismatch');
            throw new Exceptions\InvalidFormException($form);
        }

        $entity = Converter\VentilatorConverter::convertToVentilatorUpdateEntity($ventilator, $u_org_id, $form->start_using_at);

        $patient = null;

        $is_registered_patient = !is_null($entity->patient_id);

        if ($is_registered_patient) {
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
                        throw new Exceptions\InvalidFormException($form);
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

    /**
     * 非活性化。Models\Ventilator参照
     *
     * @param Form\VentilatorDeactivateForm $form
     * @param Models\User $user
     * @return \App\Http\Response\Api\VentilatorResult
     */
    public function deactivate(Form\VentilatorDeactivateForm $form, Models\User $user = null)
    {
        $id = $form->id;
        $ventilator = null;
        $logged_in = !is_null($user);

        if ($logged_in) {
            //ログインしている場合（ventilator_initializable通過済）
            if (Auth\OrgUserGate::canInitializeAllVentilator($user)) {
                //全体権限を有している場合は組織内ventilatorに対して非活性化可能
                $organization_id = $user->organization_id;
                $ventilator = Repos\VentilatorRepository::findActiveOneByOrganizationIdAndId($organization_id, $id);
            } else {
                //そうでない場合は自身の登録したのventilatorに対して非活性化可能
                $ventilator = Repos\VentilatorRepository::findActiveOneByRegisteredUserIdAndId($user->id, $id);
            }
        } else {
            //ログインしていない場合は組織に属していないventilatorに対して非活性化可能
            //$form->idとorganization_id IS NULLで取得
            $ventilator = Repos\VentilatorRepository::findActiveOneHasNoOrganizationIdById($id);
        }

        if (is_null($ventilator)) {
            throw new Exceptions\AccessDeniedException();
        }

        Support\DBUtil::Transaction(
            '呼吸器非活性化',
            function () use ($ventilator) {
                $ventilator->deactivate();
            }
        );

        return Converter\VentilatorConverter::convertToVentilatorDeactivateResult($ventilator);
    }
}
