<?php

namespace App\Services\Api;

use App\Exceptions;
use App\Models\HistoryBaseModel;
use App\Repositories as Repos;
use App\Services\Support as Support;
use App\Services\Support\Converter;
use App\Services\Support\DateUtil;
use App\Services\Support\DBUtil;

use function PHPUnit\Framework\isJson;

class PatientService
{
    use Support\Logic\CalculationLogic;

    public function create($form, $user = null)
    {
        $ventilator = Repos\VentilatorRepository::findOneById($form->ventilator_id);

        if (is_null($ventilator)) {
            $form->addError('ventilator_id', 'validation.id_not_found');
            return false;
        }

        $organization_id = null;

        if(!is_null($user)) {
            $organization_id = $user->organization_id;
        }

        if(!is_null($ventilator->organization_id)){
            $organization_id = $ventilator->organization_id;
        }

        //同一組織内に同じ患者コードが存在するかどうか
        $exists = !is_null($organization_id) && !is_null($form->patient_code) && Repos\PatientRepository::existsByPatientCodeAndOrganizationId($form->patient_code, $organization_id);

        if ($exists) {
            $form->addError('patient_code', 'validation.duplicated_patient_code');
            return false;
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

    public function getPatientResult($form)
    {
        $patient = Repos\PatientRepository::findOneById($form->id);

        if (is_null($patient)) {
            $form->addError('id', 'validation.id_not_found');
            return false;
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

    public function update($form)
    {
        $patient = Repos\PatientRepository::findOneById($form->id);

        $isRegisteredPatient = !is_null($patient);

        if (!$isRegisteredPatient) {
            $form->addError('id', 'validation.id_not_found');
            return false;
        }

        $exists = false;

        // $exists = true の場合（前提：患者組織ID有）
        // フォームとアップデート先の患者コードが同一ではなく、組織内に同じ患者コードが存在した場合
        // アップデート先の患者コードが存在せず、組織内にフォームと同じ患者コードが存在した場合
        if (!is_null($patient->organization_id)) {
            // フォームの患者コードが患者所属組織内に存在するかどうか
            if(!is_null($form->patient_code)) {
                $is_match_patient_code = !is_null($patient->patient_code) && $form->patient_code === $patient->patient_code;
                
                $exists = !$is_match_patient_code && Repos\PatientRepository::existsByPatientCodeAndOrganizationId($form->patient_code, $patient->organization_id);
            }
        }
        
        if ($exists) {
            $form->addError('patient_code', 'validation.duplicated_patient_code');
            return false;
        }

        $entity = Converter\PatientConverter::convertToUpdateEntity(
            $patient,
            $form->patient_code,
            $form->height,
            $form->gender,
            $form->weight
        );

        DBUtil::Transaction(
            '患者情報更新',
            function () use ($entity) {
                $entity->save();
            }
        );

        //組織の設定値が存在すればそっちの値を使用
        $organization_setting = null;

        if (!is_null($patient->organization_id)) {
            $organization_setting = Repos\OrganizationSettingRepository::findOneByOrganizationId($patient->organization_id);
        }

        $vt_per_kg = !is_null($organization_setting) ? $organization_setting->vt_per_kg : config('calc.default.vt_per_kg');

        //理想体重の算出
        $ideal_weight = strval($this->calcIdealWeight(floatval($form->height), $form->gender));

        $predicted_vt = $this->calcPredictedVt(floatval($ideal_weight), $vt_per_kg);

        return Converter\PatientConverter::convertToPatientResult($entity, $predicted_vt);
    }

    public function getPatientValueResult($form)
    {
        $patient = Repos\PatientRepository::findOneById($form->id);

        if (is_null($patient)) {
            $form->addError('id', 'validation.id_not_found');
            return false;
        }

        $patient_value = Repos\PatientValueRepository::findOneByPatientId($form->id);

        return Converter\PatientConverter::convertToPatientValueResult($patient->patient_code, $patient_value);
    }

    public function createPatientValue($form, $user)
    {
        $patient = Repos\PatientRepository::findOneById($form->id);

        if (is_null($patient)) {
            $form->addError('id', 'validation.id_not_found');
            return false;
        }

        $patient_value = Repos\PatientValueRepository::findOneByPatientId($form->id);

        if (!is_null($patient_value)) {
            $form->addError('id', 'validation.duplicated_patient_id');
            return false;
        }

        $registered_at = DateUtil::toDatetimeStr(DateUtil::now());

        $entity = Converter\PatientConverter::convertToPatientValueEntity(
            $form->id,
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

    public function updatePatientValue($form, $user)
    {
        $patient = Repos\PatientRepository::findOneById($form->id);

        if (is_null($patient)) {
            $form->addError('id', 'validation.id_not_found');
            return false;
        }

        // 編集元データ取得
        $old_patient_value = Repos\PatientValueRepository::findOneByPatientId($form->id);

        if (is_null($old_patient_value)) {
            $form->addError('id', 'validation.has_not_been_observed');
            return false;
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
                    $user->id);
                $delete_history->save();

                //編集後データ登録
                $new_patient_value->save();

                //登録履歴追加
                $create_history = Converter\HistoryConverter::convertToHistoryEntity(
                    $new_patient_value,
                     HistoryBaseModel::CREATE,
                      $user->id);
                      
                $create_history->save();
            }
        );

        return Converter\PatientConverter::convertToPatientValueUpdateResult($patient->id, $patient->patient_code);
    }
}
