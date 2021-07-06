<?php

namespace App\Services\Api;

use App\Exceptions;
use App\Http\Response as Response;
use App\Models\HistoryBaseModel;
use App\Repositories as Repos;
use App\Services\Support as Support;
use App\Services\Support\Converter;
use App\Services\Support\DBUtil;
use App\Services\Support\DateUtil;

class VentilatorValueService
{
    use Support\Logic\CalculationLogic;

    /**
     * ventilator_valueを取得する
     *
     * @param [type] $form
     * @return void
     */
    public function getVentilatorValueResult($form)
    {
        $ventilator_value = Repos\VentilatorValueRepository::findOneById($form->id);

        if (is_null($ventilator_value)) {
            $form->addError('ventilator_value_id', 'validation.id_not_found');
            return false;
        }

        $registered_user_id = $ventilator_value->registered_user_id;

        $registered_user_name = !is_null($registered_user_id) ? Repos\UserRepository::findOneById($registered_user_id)->name : null;

        return Converter\VentilatorValueConverter::convertToVentilatorValueResult($ventilator_value, $registered_user_name);
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

        $patient = Repos\PatientRepository::findOneById($form->patient_id);

        if (is_null($patient)) {
            $form->addError('patient_id', 'validation.id_not_found');
            return false;
        }

        $appkey_id = Repos\AppkeyRepository::findOneByAppkey($appkey)->id;

        $registered_user_id = !is_null($user) ? $user->id : null;

        //組織の設定値が存在すればそっちの値を使用
        $organization_setting = null;

        if (!is_null($user)) {
            $organization_setting = Repos\OrganizationSettingRepository::findOneByOrganizationId($user->organization_id);
        }

        $vt_per_kg = !is_null($organization_setting) ? $organization_setting->vt_per_kg : config('calc.default.vt_per_kg');

        $total_flow = $this->calcTotalFlow($form->air_flow, $form->o2_flow);

        $estimated_vt = $this->calcEstimatedVt($form->i_avg, $total_flow);

        $estimated_mv = $this->calcEstimatedMv($estimated_vt, $form->rr);

        $estimated_peep = $this->calcEstimatedPeep($form->airway_pressure);

        $fio2 = $this->calcFio2($form->air_flow, $form->o2_flow);

        $registered_at = DateUtil::toDatetimeStr(DateUtil::now());

        $height = $patient->height;

        $weight = $patient->weight;

        $gender = $patient->gender;

        $ideal_weight = $this->calcIdealWeight($height, $gender);

        $entity = Converter\VentilatorValueConverter::convertToVentilatorValueEntity(
            $form->ventilator_id,
            $height,
            $weight,
            $gender,
            $ideal_weight,
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
            $registered_at,
            $appkey_id,
            $registered_user_id,
        );

        DBUtil::Transaction(
            '機器観察研究データ登録',
            function () use ($entity, $registered_user_id) {
                $entity->save();
                //登録履歴追加
                $history = Converter\HistoryConverter::convertToHistoryEntity($entity, HistoryBaseModel::CREATE, $registered_user_id);
                $history->save();
            }
        );

        return Converter\VentilatorValueConverter::convertToVentilatorValueRegistrationResult($entity);
    }

    /**
     * 機器観察研究データを更新する
     * ここでは編集後のデータをインサートし、編集元のデータにdeleted_atを記録することをもって「更新」とする。
     * @param [type] $form
     * @return void
     */
    public function update($form, $user)
    {
        $ventilator_value = Repos\VentilatorValueRepository::findOneById($form->id);

        if (is_null($ventilator_value)) {
            $form->addError('ventilator_value_id', 'validation.id_not_found');
            return false;
        }

        //編集前データの複製
        $ventilator_value_copy = $ventilator_value->replicate();


        //編集後データの再計算
        $organization_setting = Repos\OrganizationSettingRepository::findOneByOrganizationId($user->organization_id);

        $vt_per_kg = !is_null($organization_setting) ? $organization_setting->vt_per_kg : config('calc.default.vt_per_kg');

        $total_flow = $this->calcTotalFlow($form->air_flow, $form->o2_flow);

        $ideal_weight = $this->calcIdealWeight($form->height, $form->gender);

        $predicted_vt = $this->calcPredictedVt($ideal_weight, $vt_per_kg);

        $estimated_vt = $this->calcEstimatedVt($ventilator_value_copy->inspiratory_time, $total_flow);

        $estimated_mv = $this->calcEstimatedMv($estimated_vt, $ventilator_value_copy->rr);

        $estimated_peep = $this->calcEstimatedPeep($form->airway_pressure);

        $fio2 = $this->calcFio2($form->air_flow, $form->o2_flow);

        //編集後データの挿入
        $entity = Converter\VentilatorValueConverter::convertToVentilatorValueUpdateEntity(
            $ventilator_value_copy,
            $form->height,
            $form->gender,
            $ideal_weight,
            $form->airway_pressure,
            $form->air_flow,
            $form->o2_flow,
            $vt_per_kg,
            $predicted_vt,
            $estimated_vt,
            $estimated_mv,
            $estimated_peep,
            $fio2,
            $total_flow,
            $form->weight,
            $form->status_use,
            $form->status_use_other,
            $form->spo2,
            $form->etco2,
            $form->pao2,
            $form->paco2
        );

        //編集元にdeleted_atを記録
        $ventilator_value->deleted_at = DateUtil::toDatetimeStr(DateUtil::now());

        DBUtil::Transaction(
            '編集後データの挿入',
            function () use ($entity, $ventilator_value, $user) {
                //編集前データ削除
                $ventilator_value->save();
                //削除履歴追加
                $delete_history = Converter\HistoryConverter::convertToHistoryEntity($ventilator_value, HistoryBaseModel::DELETE, $user->id);
                $delete_history->save();

                //編集後データ登録
                $entity->save();
                //登録履歴追加
                $create_history = Converter\HistoryConverter::convertToHistoryEntity($entity, HistoryBaseModel::CREATE, $user->id);
                $create_history->save();
            }
        );

        return Converter\VentilatorValueConverter::convertToVentilatorValueUpdateResult($entity->id, DateUtil::toDatetimeStr($entity->created_at));
    }

    public function getVentilatorValueListResult($form)
    {
        $search_values = $this->buildVentilatorValueSearchValues($form->ventilator_id, $form->fixed_flg);

        $ventilator_values = Repos\VentilatorValueRepository::findBySeachValuesAndLimitOffsetOrderByRegisteredAtDesc($search_values, $form->limit, $form->offset);

        $data = array_map(
            function ($ventilator_value) {
                return Converter\VentilatorValueConverter::convertToVentilatorValueListElm($ventilator_value->id, $ventilator_value->registered_at, $ventilator_value->registered_user_name);
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
