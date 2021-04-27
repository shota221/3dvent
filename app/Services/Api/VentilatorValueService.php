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

class VentilatorValueService
{
    use Support\Logic\CalculationLogic;

    /**
     * 呼吸器IDから最新の機器関連値を取得する
     *
     * @param [type] $form
     * @return void
     */
    public function getVentilatorValueResult($form)
    {
        // if (!Repos\VentilatorValueRepository::existsByVentilatorId($form->ventilator_id)) {
        //     $form->addError('ventilator_id', 'validation.id_not_found');
        //     return false;
        // }

        // $ventilator_value = Repos\VentilatorValueRepository::findOneByVentilatorId($form->ventilator_id);

        // return Converter\VentilatorConverter::convertToVentilatorValueResult($ventilator_value);
        return json_decode(Converter\VentilatorConverter::convertToDetailVentilatorValueResult(), true);
    }

    /**
     * 機器関連データ必須項目登録
     * 呼吸器使用時にリアルタイムでインサートされる
     * @param [type] $form
     * @param [type] $user_token
     * @param [type] $appkey
     * @return void
     */
    public function create($form, $user, $appkey)
    {
        if (!Repos\VentilatorRepository::existsById($form->ventilator_id)) {
            $form->addError('ventilator_id', 'validation.id_not_found');
            return false;
        }

        $registered_user_id = !is_null($user) ? $user->id : null;
        //TODO ユーザー所属組織の設定値を取得
        $vt_per_kg = 6;

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
            $registered_user_id,
            $appkey->id
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
     * 機器観察研究データを更新する
     *
     * @param [type] $form
     * @return void
     */
    public function update($form)
    {
        return json_decode(Converter\VentilatorConverter::convertToDetailVentilatorValueUpdateResult(), true);
    }

    public function getVentilatorValueListResult($form)
    {
        $search_values = $this->buildVentilatorValueSearchValues($form->ventilator_id, $form->fixed_flg);

        $ventilator_values = Repos\VentilatorValueRepository::findBySeachValuesAndLimitOffsetOrderByRegisteredAtDesc($search_values, $form->limit, $form->offset);

        $data = array_map(
            function ($ventilator_value) {
                return Converter\VentilatorConverter::convertToVentilatorValueListElm($ventilator_value->id, $ventilator_value->registered_at, $ventilator_value->registered_user_name);
            },
            $ventilator_values->all()
        );

        return new Response\ListJsonResult($data);
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
