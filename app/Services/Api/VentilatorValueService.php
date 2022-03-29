<?php

namespace App\Services\Api;

use App\Exceptions;
use App\Http\Auth;
use App\Http\Forms\Api as Form;
use App\Http\Response as Response;
use App\Models;
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
    public function getVentilatorValueResult(Form\VentilatorValueShowForm $form)
    {
        $ventilator_value = Repos\VentilatorValueRepository::findOneById($form->id);

        if (is_null($ventilator_value)) {
            $form->addError('ventilator_value_id', 'validation.id_not_found');
            throw new Exceptions\InvalidFormException($form);
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
    public function create(Form\VentilatorValueCreateForm $form, Models\User $user = null, Models\Appkey $appkey)
    {
        $ventilator_id = $form->ventilator_id;

        $ventilator_exists = Repos\VentilatorRepository::existsById($ventilator_id);

        if (!$ventilator_exists) {
            $form->addError('ventilator_id', 'validation.id_not_found');
            throw new Exceptions\InvalidFormException($form);
        }

        $patient = Repos\PatientRepository::findOneById($form->patient_id);

        if (is_null($patient)) {
            $form->addError('patient_id', 'validation.id_not_found');
            throw new Exceptions\InvalidFormException($form);
        }

        $appkey_id = $appkey->id;

        $registered_user_id = !is_null($user) ? $user->id : null;

        //組織の設定値が存在すればそっちの値を使用
        $organization_setting = null;

        if (!is_null($user)) {
            $organization_setting = Repos\OrganizationSettingRepository::findOneByOrganizationId($user->organization_id);
        }

        $vt_per_kg = !is_null($organization_setting) ? $organization_setting->vt_per_kg : config('calc.default.vt_per_kg');

        $total_flow = $this->calcTotalFlow($form->air_flow, $form->o2_flow);

        $estimated_mv = $this->calcEstimatedMv($form->i_avg, $form->rr, $total_flow, $form->airway_pressure);

        $estimated_vt = $this->calcEstimatedVt($form->i_avg, $form->rr, $total_flow, $form->airway_pressure);

        $estimated_peep = $this->calcEstimatedPeep($form->airway_pressure);

        $fio2 = $this->calcFio2($form->air_flow, $form->o2_flow);

        $registered_at = DateUtil::now();

        $height = $patient->height;

        $weight = $patient->weight;

        $gender = $patient->gender;

        $ideal_weight = $this->calcIdealWeight($height, $gender);

        //最後値（指定のventilator_idについて最新の設定値）。deletedでない設定値に対してただ一つ存在。
        $latest_flg = Models\VentilatorValue::LATEST;

        //セクションスタート時の設定値であるかどうか。以下の場合にINITIAL判定。
        //1.指定のventilator_idのLATESTな設定値のregistered_atからventilator_value_scan_interval以上時間が経過している。
        //2.指定のventilator_idに対して設定値が存在しない。

        $initial_flg = Models\VentilatorValue::NOT_INITIAL;

        $status_use = null;

        $status_use_other = '';
        
        $ventilator_value_exists = Repos\VentilatorValueRepository::existsByVentilatorId($ventilator_id);

        $latest_ventilator_value = null;

        if ($ventilator_value_exists) {
            $interval = config('system.fixed_flg_interval');
            $latest_ventilator_value = Repos\VentilatorValueRepository::findOneLatestByVentilatorId($ventilator_id);
            $latest_ventilator_value->latest_flg = Models\VentilatorValue::NOT_LATEST;
            $diff_from_latest_ventilator_value = DateUtil::parseToDatetime($latest_ventilator_value->registered_at)->diffInMinutes($registered_at);
            $status_use = $latest_ventilator_value->status_use;
            $status_use_other = $latest_ventilator_value->status_use_other;
            if( $diff_from_latest_ventilator_value >= $interval ){
                $initial_flg = Models\VentilatorValue::INITIAL;
            };
        } else {
            $initial_flg = Models\VentilatorValue::INITIAL;
        }

        $entity = Converter\VentilatorValueConverter::convertToVentilatorValueEntity(
            $ventilator_id,
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
            $initial_flg,
            $latest_flg,
            $status_use,
            $status_use_other,
        );

        DBUtil::Transaction(
            '機器観察研究データ登録',
            function () use ($entity, $latest_ventilator_value, $registered_user_id) {
                $entity->save();
                //登録履歴追加
                $history = Converter\HistoryConverter::convertToHistoryEntity($entity, HistoryBaseModel::CREATE, $registered_user_id);
                $history->save();

                //latest_flg更新
                if (!is_null($latest_ventilator_value)) {
                    $latest_ventilator_value->save();
                }
            }
        );

        return Converter\VentilatorValueConverter::convertToVentilatorValueRegistrationResult($entity);
    }

    public function getVentilatorValueListResult(Form\VentilatorValueListForm $form)
    {
        $ventilator_id = $form->ventilator_id;

        $is_active_ventilator = Repos\VentilatorRepository::IsActiveById($ventilator_id);

        if (!$is_active_ventilator) {
            $form->addError('ventilator_id', 'validation.id_not_found');
            throw new Exceptions\InvalidFormException($form);
        }

        $search_values = $this->buildVentilatorValueSearchValues($ventilator_id);

        $ventilator_values = Repos\VentilatorValueRepository::findBySeachValuesAndLimitOffsetOrderByRegisteredAtDesc($search_values, $form->limit, $form->offset);

        $data = array_map(
            function ($ventilator_value) {
                $is_initial = boolval($ventilator_value->initial_flg);
                $is_latest = boolval($ventilator_value->latest_flg);
                $is_fixed = boolval($ventilator_value->fixed_flg);
                return Converter\VentilatorValueConverter::convertToVentilatorValueListElm($ventilator_value->id, $ventilator_value->registered_at, $ventilator_value->registered_user_name, $is_initial, $is_latest, $is_fixed);
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
