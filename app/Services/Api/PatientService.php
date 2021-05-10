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

use function PHPUnit\Framework\isJson;

class PatientService
{
    use Support\Logic\CalculationLogic;

    public function create($form, $user = null)
    {
        $organization_id = !is_null($user) ? $user->organization_id : null;

        //同一組織内に同じ患者コードが存在するかどうか
        $exists = !is_null($organization_id) && !is_null($form->patient_code) && Repos\PatientRepository::existsByPatientCodeAndOrganizationId($form->patient_code, $organization_id);

        if ($exists) {
            $form->addError('patient_code', 'validation.duplicated_patient_code');
            return false;
        }


        $entity = Converter\PatientConverter::convertToEntity(
            $form->height,
            $form->gender,
            $form->patient_code,
            $organization_id
        );

        $ventilator = Repos\VentilatorRepository::findOneById($form->ventilator_id);

        if (is_null($ventilator)) {
            $form->addError('ventilator_id', 'validation.id_not_found');
            return false;
        }

        DBUtil::Transaction(
            '患者情報登録',
            function () use ($entity) {
                $entity->save();
            }
        );

        $ventilator->patient_id = $entity->id;

        DBUtil::Transaction(
            '呼吸器に患者ID登録',
            function () use ($ventilator) {
                $ventilator->save();
            }
        );

        //組織の設定値が存在すればそっちの値を使用
        $organization_setting = !is_null($organization_id) ? Repos\OrganizationSettingRepository::findOneByOrganizationId($organization_id) : null;

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
        $organization_setting = !is_null($patient->organization_id) ? Repos\OrganizationSettingRepository::findOneByOrganizationId($patient->organization_id) : null;

        $vt_per_kg = !is_null($organization_setting) ? $organization_setting->vt_per_kg : config('calc.default.vt_per_kg');

        //理想体重の算出
        $ideal_weight = strval($this->calcIdealWeight(floatval($patient->height), $patient->gender));

        $predicted_vt = $this->calcPredictedVt(floatval($ideal_weight), $vt_per_kg);

        return Converter\PatientConverter::convertToPatientResult($patient, $predicted_vt);
    }

    public function update($form)
    {
        $patient = Repos\PatientRepository::findOneById($form->id);

        if (is_null($patient)) {
            $form->addError('id', 'validation.id_not_found');
            return false;
        }

        //フォームとアップデート先両方に患者コードがあり、それらが同一でないかつ、同一組織内に同じ患者コードが存在するかどうか
        $exists =  !is_null($form->patient_code) && !is_null($patient->patient_code) && $form->patient_code !== $patient->patient_code && Repos\PatientRepository::existsByPatientCodeAndOrganizationId($form->patient_code, $patient->organization_id);

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

        $vt_per_kg = config('calc.default.vt_per_kg');

        //組織の設定値が存在すればそっちの値を使用
        if (!is_null($patient->organization_id) && !is_null($organization_setting = Repos\OrganizationSettingRepository::findOneByOrganizationId($patient->organization_id))) {
            $vt_per_kg = $organization_setting->vt_per_kg;
        }

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

        if(!is_null($patient_value)) {
            $form->addError('id', 'validation.duplicated_patient_id');
            return false;
        }

        $entity = Converter\PatientConverter::convertToPatientValueEntity(
            $form->id,
            $user->id,
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
            $form->adverse_event_contents,
        );

        DBUtil::Transaction(
            '患者観察研究データ登録',
            function()use($entity){
                $entity->save();
            }
        );

        return Converter\PatientConverter::convertToPatientValueUpdateResult($patient->patient_code);
    }

    public function updatePatientValue($form, $user)
    {
        $patient = Repos\PatientRepository::findOneById($form->id);

        if (is_null($patient)) {
            $form->addError('id', 'validation.id_not_found');
            return false;
        }

        $patient_value = Repos\PatientValueRepository::findOneByPatientId($form->id);

        if (is_null($patient_value)) {
            $form->addError('id', 'validation.has_not_been_observed');
            return false;
        }

        $entity = Converter\PatientConverter::convertToPatientValueUpdateEntity(
            $patient_value,
            $user->id,
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
            $form->adverse_event_contents,
        );

        DBUtil::Transaction(
            '患者観察研究データ更新',
            function()use($entity){
                $entity->save();
            }
        );

        return Converter\PatientConverter::convertToPatientValueUpdateResult($patient->patient_code);
    }
}
