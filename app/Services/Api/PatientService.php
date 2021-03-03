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

    public function create($form)
    {
        //理想体重の算出
        $form->ideal_weight = strval($this->calcIdealWeight(floatval($form->height), $form->gender));
        
        $entity = Converter\PatientConverter::convertToEntity($form);

        DBUtil::Transaction(
            '患者情報登録',
            function () use ($entity) {
                $entity->save();
            }
        );

        //TODO ユーザー設定からの取得
        $vt_per_kg = 6;

        $predicted_vt = $this->calcPredictedVt(floatval($entity->ideal_weight),$vt_per_kg);

        return Converter\PatientConverter::convertToPatientRegistrationResult($entity, $predicted_vt);
    }

    public function getPatientResult($form)
    {
        $patient = Repos\PatientRepository::findOneById($form->id);

        if(is_null($patient)) {
            $form->addError('id','validation.id_not_found');
            return false;
        }
        //TODO ユーザー設定からの取得
        $vt_per_kg = 6;

        $predicted_vt = $this->calcPredictedVt(floatval($patient->ideal_weight),$vt_per_kg);

        if(isJson($patient->other_attrs)) {
            $patient->other_attrs =  json_decode($patient->other_attrs);
        }

        return Converter\PatientConverter::convertToPatientResult($patient, $predicted_vt);
    }

    public function update($form)
    {
        $patient = Repos\PatientRepository::findOneById($form->id);

        if(is_null($patient)) {
            $form->addError('id','validation.id_not_found');
            return false;
        }

        $entity = Converter\PatientConverter::convertToUpdateEntity($form,$patient);

        //理想体重更新
        $entity->ideal_weight = strval($this->calcIdealWeight(floatval($entity->height), $entity->gender));

        DBUtil::Transaction(
            '患者情報更新',
            function () use ($entity) {
                $entity->save();
            }
        );

        //TODO ユーザー設定からの取得
        $vt_per_kg = 6;

        $predicted_vt = $this->calcPredictedVt(floatval($entity->ideal_weight),$vt_per_kg);

        return Converter\PatientConverter::convertToPatientResult($entity, $predicted_vt);
    }
}
