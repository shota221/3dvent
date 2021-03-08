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
    use Support\Logic\CalculationLogic;

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

    public function getVentilatorValueResult($form)
    {
        if (!Repos\VentilatorValueRepository::existsByVentilatorId($form->ventilator_id)) {
            $form->addError('ventilator_id','validation.id_not_found');
            return false;
        }

        $ventilator_value = Repos\VentilatorValueRepository::findOneByVentilatorId($form->ventilator_id);

        return Converter\VentilatorConverter::convertToVentilatorValueResult($ventilator_value);
    }

    public function createVentilatorValue($form,$user_token,$appkey)
    {
        if (!Repos\VentilatorRepository::existsById($form->ventilator_id)) {
            $form->addError('ventilator_id','validation.id_not_found');
            return false;
        }
        if (!Repos\AppkeyRepository::existsByAppkey($appkey)) {
            $form->addError('X-App-Key','validation.appkey_not_found');
            return false;
        }

        if (!is_null($user_token)) {
            //TODO Auth:user()からの取得
            $form->user_id = 3;
            //TODO ユーザー所属組織の設定値を取得
            $form->vt_per_kg = 6;
        }

        $form->appkey_id = Repos\AppkeyRepository::findOneByAppkey($appkey)->id;
        
        $form->total_flow = $this->calcTotalFlow($form->air_flow,$form->o2_flow);
        
        $form->estimated_vt = $this->calcEstimatedVt($form->i_avg, $form->total_flow);

        $form->estimated_mv = $this->calcEstimatedMv($form->estimated_vt, $form->rr);

        $form->estimated_peep = $this->calcEstimatedPeep($form->airway_pressure);

        $form->fio2 = $this->calcFio2($form->air_flow, $form->o2_flow);

        $patient = Repos\PatientRepository::findOneById($form->patient_id);

        $entity = Converter\VentilatorConverter::convertToVentilatorValueEntity($form,$patient);

        DBUtil::Transaction(
            '機器関連情報登録',
            function () use ($entity) {
                $entity->save();
            }
        );

        return Converter\VentilatorConverter::convertToVentilatorValueRegistrationResult($entity);
    }

    public function updateVentilatorValue($form)
    {
        if (!Repos\VentilatorValueRepository::existsByVentilatorId($form->ventilator_id)) {
            $form->addError('ventilator_id','validation.id_not_found');
            return false;
        }

        $ventilator_value = Repos\VentilatorValueRepository::findOneByVentilatorId($form->ventilator_id);

        $entity = Converter\VentilatorConverter::convertToVentilatorValueUpdateEntity($form,$ventilator_value);

        DBUtil::Transaction(
            '最終設定フラグ更新',
            function () use ($entity) {
                $entity->save();
            }
        );

        return Converter\VentilatorConverter::convertToVentilatorValueUpdateResult($entity);
    }
}