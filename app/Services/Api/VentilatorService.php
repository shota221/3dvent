<?php

namespace App\Services\Api;

use App\Exceptions;
use App\Models;
use App\Http\Forms\Api as Form;
use App\Http\Response as Response;
use App\Repositories as Repos;
use App\Models\Report;
use App\Services\Support as Support;
use App\Services\Support\Client\ReverseGeocodingClient;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Services\Support\Converter;
use App\Services\Support\DBUtil;
use App\Services\Support\DateUtil;

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

    public function create($form, $user = null)
    {
        if (!is_null($user)) {
            $registered_user_id = $user->id;
            $organization_id = $user->organization_id;
        }

        $serial_number = substr($form->gs1_code, -5);

        if (!is_null($form->latitude) && !is_null($form->longitude)) {
            $nearest_city = (new Support\Client\ReverseGeocodingApiClient)->getReverseGeocodingData($form->latitude, $form->longitude, 13)->display_name;
        }

        $entity = Converter\VentilatorConverter::convertToVentilatorEntity($form->gs1_code, $serial_number, $form->latitude, $form->longitude, $nearest_city, $organization_id, $registered_user_id);

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

    public function getVentilatorValueResult($form)
    {
        if (!Repos\VentilatorValueRepository::existsByVentilatorId($form->ventilator_id)) {
            $form->addError('ventilator_id', 'validation.id_not_found');
            return false;
        }

        $ventilator_value = Repos\VentilatorValueRepository::findOneByVentilatorId($form->ventilator_id);

        return Converter\VentilatorConverter::convertToVentilatorValueResult($ventilator_value);
    }

    public function createVentilatorValue($form, $user_token, $appkey)
    {
        if (!Repos\VentilatorRepository::existsById($form->ventilator_id)) {
            $form->addError('ventilator_id', 'validation.id_not_found');
            return false;
        }
        if (!Repos\AppkeyRepository::existsByAppkey($appkey)) {
            $form->addError('X-App-Key', 'validation.appkey_not_found');
            return false;
        }

        if (!is_null($user_token)) {
            //TODO Auth:user()からの取得
            $user_id = 3;
            //TODO ユーザー所属組織の設定値を取得
            $vt_per_kg = 6;
        }

        $appkey_id = Repos\AppkeyRepository::findOneByAppkey($appkey)->id;

        $total_flow = $this->calcTotalFlow($form->air_flow, $form->o2_flow);

        $estimated_vt = $this->calcEstimatedVt($form->i_avg, $total_flow);

        $estimated_mv = $this->calcEstimatedMv($estimated_vt, $form->rr);

        $estimated_peep = $this->calcEstimatedPeep($form->airway_pressure);

        $fio2 = $this->calcFio2($form->air_flow, $form->o2_flow);

        $patient = Repos\PatientRepository::findOneById($form->patient_id);

        $entity = Converter\VentilatorConverter::convertToVentilatorValueEntity(
            $patient,
            $form->ventilator_id,
            $form->airway_pressure,
            $form->air_flow,
            $form->o2_flow,
            $form->rr,
            $form->i_avg,
            $form->e_avg,
            $vt_per_kg,
            $form->predicted_vt,
            $estimated_vt,
            $estimated_mv,
            $estimated_peep,
            $fio2,
            $total_flow,
            $user_id,
            $appkey_id
        );

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
        //人工呼吸器登録後、測定をせずに次の人工呼吸器を読み込んだ場合の処理
        if (!Repos\VentilatorValueRepository::existsByVentilatorId($form->ventilator_id)) {
            return  Converter\VentilatorConverter::convertToVentilatorValueUpdateResult();
        }

        $ventilator_value = Repos\VentilatorValueRepository::findOneByVentilatorId($form->ventilator_id);

        $fixed_at = DateUtil::toDatetimeStr(DateUtil::now());

        $entity = Converter\VentilatorConverter::convertToVentilatorValueUpdateEntity($ventilator_value, $form->fixed_flg, $fixed_at);

        DBUtil::Transaction(
            '最終設定フラグ更新',
            function () use ($entity) {
                $entity->save();
            }
        );

        return  Converter\VentilatorConverter::convertToVentilatorValueUpdateResult($entity);
    }
}
