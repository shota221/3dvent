<?php

namespace App\Services\Api;

use App\Exceptions;
use App\Http\Auth;
use App\Http\Forms\Api as Form;
use App\Models;
use App\Models\HistoryBaseModel;
use App\Repositories as Repos;
use App\Services\Support as Support;
use App\Services\Support\Converter;
use App\Services\Support\DateUtil;
use App\Services\Support\DBUtil;

class PatientService
{
    use Support\Logic\CalculationLogic;

    public function create(Form\PatientCreateForm $form, Models\User $user = null)
    {
        $ventilator = Repos\VentilatorRepository::findActiveOneById($form->ventilator_id);

        if (is_null($ventilator)) {
            $form->addError('ventilator_id', 'validation.id_not_found');
            throw new Exceptions\InvalidFormException($form);
        }

        $organization_id = null;

        if (!is_null($user)) {
            $organization_id = $user->organization_id;
        }

        if (!is_null($ventilator->organization_id)) {
            $organization_id = $ventilator->organization_id;
        }

        //同一組織内に同じ患者コードが存在するかどうか
        if (!is_null($organization_id) && !is_null($form->patient_code)) {
            $exists = Repos\PatientRepository::existsByPatientCodeAndOrganizationId($form->patient_code, $organization_id);
            if ($exists) {
                $form->addError('patient_code', 'validation.duplicated_patient_code');
                throw new Exceptions\InvalidFormException($form);
            }
        }

        $entity = Converter\PatientConverter::convertToEntity(
            $form->height,
            $form->gender,
            $form->weight,
            $form->patient_code,
            $organization_id
        );

        DBUtil::Transaction(
            '患者情報登録,呼吸器に患者id登録',
            function () use ($entity, $ventilator) {
                $entity->save();
                $ventilator->patient_id = $entity->id;
                $ventilator->save();
            }
        );


        //組織の設定値が存在すればそっちの値を使用
        $organization_setting = null;

        if (!is_null($organization_id)) {
            $organization_setting = Repos\OrganizationSettingRepository::findOneByOrganizationId($organization_id);
        }

        $vt_per_kg = !is_null($organization_setting) ? $organization_setting->vt_per_kg : config('calc.default.vt_per_kg');

        //理想体重の算出
        $ideal_weight = strval($this->calcIdealWeight(floatval($form->height), $form->gender));

        $predicted_vt = $this->calcPredictedVt(floatval($ideal_weight), $vt_per_kg);

        return Converter\PatientConverter::convertToPatientRegistrationResult($entity, $predicted_vt);
    }

    public function getPatientResult(Form\PatientShowForm $form)
    {
        $patient = Repos\PatientRepository::findOneById($form->id);

        if (is_null($patient)) {
            $form->addError('id', 'validation.id_inaccessible');
            throw new Exceptions\InvalidFormException($form);
        }

        //組織の設定値が存在すればそっちの値を使用
        $organization_setting = null;

        if (!is_null($patient->organization_id)) {
            $organization_setting = Repos\OrganizationSettingRepository::findOneByOrganizationId($patient->organization_id);
        }

        $vt_per_kg = !is_null($organization_setting) ? $organization_setting->vt_per_kg : config('calc.default.vt_per_kg');

        //理想体重の算出
        $ideal_weight = strval($this->calcIdealWeight(floatval($patient->height), $patient->gender));

        $predicted_vt = $this->calcPredictedVt(floatval($ideal_weight), $vt_per_kg);

        return Converter\PatientConverter::convertToPatientResult($patient, $predicted_vt);
    }

    /**
     * @param Form\PatientUpdateForm $form
     * @return Response\Api\PatientResult
     */
    public function update(Form\PatientUpdateForm $form)
    {
        $patient = Repos\PatientRepository::findOneById($form->id);

        if (is_null($patient)) {
            $form->addError('id', 'validation.id_inaccessible');
            throw new Exceptions\InvalidFormException($form);
        }

        // アップデート先の患者に組織IDがあり、フォームに患者コードがあれば患者コードの重複確認
        //当該ID以外でフォームと同じ患者コードをもつ患者が同じ組織内に存在するかどうか
        if (!is_null($patient->organization_id) && !is_null($form->patient_code)) {
            $is_duplicated_patient_code = Repos\PatientRepository::existsByPatientCodeAndOrganizationIdExceptId($form->patient_code, $patient->organization_id, $form->id);

            if ($is_duplicated_patient_code) {
                $form->addError('patient_code', 'validation.duplicated_patient_code');
                throw new Exceptions\InvalidFormException($form);
            }
        }

        $patient->patient_code = $form->patient_code;
        $patient->weight = $form->weight;

        Support\DBUtil::Transaction(
            '患者情報更新',
            function () use ($patient) {
                $patient->save();
            }
        );

        //組織の設定値が存在すればそっちの値を使用
        $organization_setting = null;

        if (!is_null($patient->organization_id)) {
            $organization_setting = Repos\OrganizationSettingRepository::findOneByOrganizationId($patient->organization_id);
        }

        $vt_per_kg = !is_null($organization_setting) ? $organization_setting->vt_per_kg : config('calc.default.vt_per_kg');

        //理想体重の算出
        $ideal_weight = strval($this->calcIdealWeight(floatval($patient->height), $patient->gender));

        $predicted_vt = $this->calcPredictedVt(floatval($ideal_weight), $vt_per_kg);

        return Converter\PatientConverter::convertToPatientResult($patient, $predicted_vt);
    }

    public function getPatientValueResult(Form\PatientShowForm $form, Models\User $user)
    {
        $patient_id = $form->id;
        $organization_id = $user->organization_id;

        //ユーザーの組織で患者IDから患者を検索
        $patient = Repos\PatientRepository::findOneByOrganizationIdAndId($organization_id, $patient_id);

        if (is_null($patient)) {
            $form->addError('id', 'validation.id_inaccessible');
            throw new Exceptions\InvalidFormException($form);
        }

        $patient_value = Repos\PatientValueRepository::findOneByPatientId($patient_id);

        return Converter\PatientConverter::convertToPatientValueResult($patient->patient_code, $patient_value);
    }

    public function createPatientValue(Form\PatientValueForm $form, Models\User $user)
    {
        $patient_id = $form->id;
        $organization_id = $user->organization_id;

        //ユーザーの組織で患者IDから患者を検索
        $patient = Repos\PatientRepository::findOneByOrganizationIdAndId($organization_id, $patient_id);

        if (is_null($patient)) {
            $form->addError('id', 'validation.id_inaccessible');
            throw new Exceptions\InvalidFormException($form);
        }

        //すでに登録済みでないか
        $patient_value_exists = Repos\PatientValueRepository::existsByPatientId($patient_id);

        if ($patient_value_exists) {
            $form->addError('id', 'validation.duplicated_patient_id');
            throw new Exceptions\InvalidFormException($form);
        }

        $registered_at = DateUtil::toDatetimeStr(DateUtil::now());

        $entity = Converter\PatientConverter::convertToPatientValueEntity(
            $patient_id,
            $user->id,
            $registered_at,
            $form->opt_out_flg,
            $form->age,
            $form->vent_disease_name,
            $form->other_disease_name_1,
            $form->other_disease_name_2,
            $form->used_place,
            $form->hospital,
            $form->national,
            $form->discontinuation_at,
            $form->outcome,
            $form->treatment,
            $form->adverse_event_flg,
            $form->adverse_event_contents
        );

        DBUtil::Transaction(
            '患者観察研究データ登録',
            function () use ($entity, $user) {
                $entity->save();
                //登録履歴追加
                $create_history = Converter\HistoryConverter::convertToHistoryEntity($entity, HistoryBaseModel::CREATE, $user->id);
                $create_history->save();
            }
        );

        return Converter\PatientConverter::convertToPatientValueUpdateResult($patient->id, $patient->patient_code);
    }

    public function updatePatientValue(Form\PatientValueForm $form, Models\User $user)
    {
        $patient_id = $form->id;
        $organization_id = $user->organization_id;

        //ユーザーの組織で患者IDから患者を検索
        $patient = Repos\PatientRepository::findOneByOrganizationIdAndId($organization_id, $patient_id);

        if (is_null($patient)) {
            throw new Exceptions\AccessDeniedException();
        }

        $old_patient_value = null;

        if (Auth\OrgUserGate::canEditAllPatientValue($user)) {
            //全体権限を有している場合は組織内patient_valueに対して編集可能
            $organization_id = $user->organization_id;
            $old_patient_value = Repos\PatientValueRepository::findOneByOrganizationIdAndPatientId($organization_id, $patient_id);
        } else {
            //そうでない場合は自身の登録したpatient_valueに対して編集可能
            $old_patient_value = Repos\PatientValueRepository::findOneByPatientObsUserIdAndPatientId($user->id, $patient_id);
        }

        if (is_null($old_patient_value)) {
            throw new Exceptions\AccessDeniedException();
        }

        // 編集後データ作成
        $new_patient_value = Converter\PatientConverter::convertToPatientValueEntity(
            $old_patient_value->patient_id,
            $old_patient_value->patient_obs_user_id,
            $old_patient_value->registered_at,
            $form->opt_out_flg,
            $form->age,
            $form->vent_disease_name,
            $form->other_disease_name_1,
            $form->other_disease_name_2,
            $form->used_place,
            $form->hospital,
            $form->national,
            $form->discontinuation_at,
            $form->outcome,
            $form->treatment,
            $form->adverse_event_flg,
            $form->adverse_event_contents
        );

        //編集元にdeleted_atを記録
        $old_patient_value->deleted_at = DateUtil::toDatetimeStr(DateUtil::now());

        DBUtil::Transaction(
            '編集後データの挿入',
            function () use ($new_patient_value, $old_patient_value, $user) {
                //編集元データ論理削除
                $old_patient_value->save();

                //削除履歴追加
                $delete_history = Converter\HistoryConverter::convertToHistoryEntity(
                    $old_patient_value,
                    HistoryBaseModel::DELETE,
                    $user->id
                );
                $delete_history->save();

                //編集後データ登録
                $new_patient_value->save();

                //登録履歴追加
                $create_history = Converter\HistoryConverter::convertToHistoryEntity(
                    $new_patient_value,
                    HistoryBaseModel::CREATE,
                    $user->id
                );

                $create_history->save();
            }
        );

        return Converter\PatientConverter::convertToPatientValueUpdateResult($patient->id, $patient->patient_code);
    }
}
