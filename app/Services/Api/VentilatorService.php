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

    /**
     * gs1コードから呼吸器情報を取得する
     *
     * @param [type] $form
     * @return void
     */
    public function getVentilatorResult($form)
    {
        if (!Repos\VentilatorRepository::existsByGs1Code($form->gs1_code)) {

            return Converter\VentilatorConverter::convertToVentilatorResult();
        }

        $ventilator = Repos\VentilatorRepository::findOneByGs1Code($form->gs1_code);

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

        $entity = Converter\VentilatorConverter::convertToVentilatorEntity($form->gs1_code, $serial_number, $form->latitude, $form->longitude, $city, $organization_id, $registered_user_id);

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

    /**
     * 呼吸器IDから最新の機器関連値を取得する
     *
     * @param [type] $form
     * @return void
     */
    public function getVentilatorValueResult($form)
    {
        if (!Repos\VentilatorValueRepository::existsByVentilatorId($form->ventilator_id)) {
            $form->addError('ventilator_id', 'validation.id_not_found');
            return false;
        }

        $ventilator_value = Repos\VentilatorValueRepository::findOneByVentilatorId($form->ventilator_id);

        return Converter\VentilatorConverter::convertToVentilatorValueResult($ventilator_value);
    }

    /**
     * 機器関連データ必須項目登録
     * 呼吸器使用時にリアルタイムでインサートされる
     * @param [type] $form
     * @param [type] $user_token
     * @param [type] $appkey
     * @return void
     */
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

    /**
     * 最終設定フラグを更新する
     *
     * @param [type] $form
     * @return void
     */
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

    public function getVentilatorValueListResult($form)
    {
        $search_values = $this->buildVentilatorValueSearchValues($form->ventilator_id, 1);

        $ventilator_values = Repos\VentilatorValueRepository::findBySeachValuesAndOffsetAndLimit($search_values, $form->limit, $form->offset);

        $data = array_map(
            function ($ventilator_value) {
                $registered_user_name = null;

                if (!is_null($ventilator_value->user_id)) {
                    $registered_user_name = Repos\UserRepository::findOneById($ventilator_value->user_id)->name;
                }

                return Converter\VentilatorConverter::convertToVentilatorValueListElm($ventilator_value->id, $ventilator_value->registered_at, $registered_user_name);
            },
            $ventilator_values->all()
        );

        return new Response\ListJsonResult($data);
    }

    //TODO 以下補完作業
    public function getDetailVentilatorValueResult()
    {
        return json_decode(Converter\VentilatorConverter::convertToDetailVentilatorValueResult(), true);
    }

    public function updateDetailVentilatorValue()
    {
        return json_decode(Converter\VentilatorConverter::convertToDetailVentilatorValueUpdateResult(), true);
    }

    private function buildVentilatorValueSearchValues(
        $ventilator_id,
        $fixed_flg = null,
        $user_id = null,
        $confirmed_flg = null,
        $confirmed_user_id = null
    ) {
        $search_values = [];

        $search_values['ventilator_id'] = $ventilator_id;

        $search_values['fixed_flg'] = $fixed_flg;

        $search_values['user_id'] = $user_id;

        $search_values['confirmed_flg'] = $confirmed_flg;

        $search_values['confirmed_user_id'] = $confirmed_user_id;

        return $search_values;
    }
}
