<?php

namespace App\Services\Org;

use App\Exceptions;
use App\Http\Auth;
use App\Http\Forms\Org as Form;
use App\Http\Response;
use App\Models;
use App\Models\HistoryBaseModel;
use App\Models\User;
use App\Models\VentilatorValue;
use App\Repositories as Repos;
use App\Services\Support\Converter;
use App\Services\Support\DateUtil;
use App\Services\Support\DBUtil;
use App\Services\Support\Logic\CalculationLogic;

class VentilatorValueService
{
    use CalculationLogic;
    /**
     * 機器観察研究データ一覧取得
     *
     * @param string $path
     * @param Form\VentilatorValueSearchForm $form
     * @return [type]
     */
    public function getPaginatedVentilatorValueData(
        string $path,
        User $user,
        Form\VentilatorValueSearchForm $form = null
    ) {
        $item_per_page = config('view.items_per_page');
        $offset = 0;
        $search_values = [];
        $http_query = '';

        if (!is_null($form)) {
            if (isset($form->page)) $offset = ($form->page - 1) * $item_per_page;
            $search_values = $this->buildVentilatorValueSearchValues($form);
            $http_query = '?' . http_build_query($search_values);
        }

        $organization_id = $user->organization_id;

        if (Auth\OrgUserGate::canReadAllVentilator($user)) {
            $ventilator_values = Repos\VentilatorValueRepository::searchWithUsersAndVentilatorsAndPatientsAndOrganizationsByOrganizationId(
                $organization_id,
                $search_values,
                $item_per_page,
                $offset
            );
    
            $total_count = Repos\VentilatorValueRepository::countByOrganizationIdAndSearchValues(
                $organization_id,
                $search_values);

        } else {
            $ventilator_values = Repos\VentilatorValueRepository::searchWithUsersAndVentilatorsAndPatientsAndOrganizationsByOrganizationIdAndRegisteredUserId(
                $organization_id,
                $user->id,
                $search_values,
                $item_per_page,
                $offset
            );
    
            $total_count = Repos\VentilatorValueRepository::countByOrganizationIdAndUserIdAndSearchValues(
                $organization_id,
                $user->id,
                $search_values);
        }


        return Converter\VentilatorValueConverter::convertToOrgPaginate(
            $ventilator_values,
            $total_count,
            $item_per_page,
            $path . $http_query
        );
    }

    /**
     * 機器観察研究データ詳細取得
     *
     * @param Form\VentilatorValueDetailForm $form
     * @return type
     */
    public function getOneVentilatorValueData(Form\VentilatorValueDetailForm $form, User $user)
    {
        $organization_id = $user->organization_id;

        $ventilator_value = Repos\VentilatorValueRepository::findOneWithPatientAndOrganizationAndRegisteredUserByOrganizationIdAndId($organization_id, $form->id);

        if (is_null($ventilator_value)) {
            $form->addError('id', 'validation.id_not_found');
            throw new Exceptions\InvalidFormException($form);
        }

        // 全閲覧権限が無い場合には登録者idが一致しているか確認
        if (! Auth\OrgUserGate::canReadAllVentilatorValue($user)) {
            $is_match = $ventilator_value->registered_user_id === $user->id;
            
            if (! $is_match) {
                $form->addError('id', 'validation.id_not_found');
                throw new Exceptions\InvalidFormException($form);
            }
        }

        return Converter\VentilatorValueConverter::convertToOrgVentilatorValueDetail($ventilator_value);
    }

    /**
     * 機器観察研究データを更新する
     * ここでは編集後のデータをインサートし、編集元のデータにdeleted_atを記録することをもって「更新」とする。
     * @param Form\VentialtorValueUpdateForm $form
     * @return void
     */
    public function update(Form\VentilatorValueUpdateForm $form, User $user)
    {
        $organization_id = $user->organization_id;

        $ventilator_value = Repos\VentilatorValueRepository::findOneByOrganizationIdAndId($organization_id, $form->id);

        if (is_null($ventilator_value)) {
            $form->addError('ventilator_value_id', 'validation.id_not_found');
            throw new Exceptions\InvalidFormException($form);
        }

        
        // 全編集権限が無い場合には、登録者idが一致しているか確認
        if (! Auth\OrgUserGate::canEditAllVentilatorValue($user)) {
            $is_matched = $ventilator_value->registered_user_id === $user->id;
            
            if (! $is_matched) {
                $form->addError('id', 'validation.id_not_found');
                throw new Exceptions\InvalidFormException($form);
            }
        }

        $user_id = $user->id;

        //編集後データの再計算 
        $vt_per_kg = config('calc.default.vt_per_kg');

        $organization_setting = Repos\OrganizationSettingRepository::findOneByOrganizationId($organization_id);

        if (!is_null($organization_setting)) $vt_per_kg = $organization_setting->vt_per_kg;

        $total_flow = $this->calcTotalFlow($form->air_flow, $form->o2_flow);

        $ideal_weight = $this->calcIdealWeight($form->height, $form->gender);

        $predicted_vt = $this->calcPredictedVt($ideal_weight, $vt_per_kg);

        $estimated_mv = $this->calcEstimatedMv($ventilator_value->inspiratory_time, $ventilator_value->rr, $total_flow, $form->airway_pressure);

        $estimated_vt = $this->calcEstimatedVt($ventilator_value->inspiratory_time, $ventilator_value->rr, $total_flow, $form->airway_pressure);

        $estimated_peep = $this->calcEstimatedPeep($form->airway_pressure);

        $fio2 = $this->calcFio2($form->air_flow, $form->o2_flow);

        $confirmed_flg = $form->confirmed_flg ?? $ventilator_value->confirmed_flg;

        $confirmed_at = null;

        $confirmed_user_id = null;

        if ($confirmed_flg === VentilatorValue::CONFIRM) {
            $confirmed_at = DateUtil::now();
            $confirmed_user_id = $user_id;
        }

        //編集後データの作成
        $entity = Converter\VentilatorValueConverter::convertToVentilatorValueEntity(
            $ventilator_value->ventilator_id,
            $form->height,
            $form->weight,
            $form->gender,
            $ideal_weight,
            $form->airway_pressure,
            $form->air_flow,
            $form->o2_flow,
            $ventilator_value->rr,
            $ventilator_value->inspiratory_time,
            $ventilator_value->expiratory_time,
            $vt_per_kg,
            $predicted_vt,
            $estimated_vt,
            $estimated_mv,
            $estimated_peep,
            $fio2,
            $total_flow,
            $ventilator_value->registered_at,
            $ventilator_value->appkey_id,
            $ventilator_value->registered_user_id,
            $ventilator_value->initial_flg,
            $ventilator_value->latest_flg,
            $form->status_use,
            $form->status_use_other,
            $form->spo2,
            $form->etco2,
            $form->pao2,
            $form->paco2,
            $ventilator_value->fixed_flg,
            $ventilator_value->ventilator_value_scanned_at,
            $ventilator_value->fixed_at,
            $confirmed_flg,
            $confirmed_at,
            $confirmed_user_id
        );

        //編集元にdeleted_atを記録
        $ventilator_value->deleted_at = DateUtil::toDatetimeStr(DateUtil::now());

        DBUtil::Transaction(
            '編集後データの挿入',
            function () use ($entity, $ventilator_value, $user_id) {
                //編集前データ削除
                $ventilator_value->save();
                //削除履歴追加
                $delete_history = Converter\HistoryConverter::convertToHistoryEntity($ventilator_value, HistoryBaseModel::DELETE, $user_id);
                $delete_history->save();

                //編集後データ登録
                $entity->save();
                //登録履歴追加
                $create_history = Converter\HistoryConverter::convertToHistoryEntity($entity, HistoryBaseModel::CREATE, $user_id);
                $create_history->save();
            }
        );

        return new Response\SuccessJsonResult;
    }


    private function buildVentilatorValueSearchValues(Form\VentilatorValueSearchForm $form)
    {
        $search_values = [];
        if (isset($form->ventilator_id)) $search_values['ventilator_id'] = $form->ventilator_id;
        if (isset($form->gs1_code)) $search_values['gs1_code'] = $form->gs1_code;
        if (isset($form->patient_code)) $search_values['patient_code'] = $form->patient_code;
        if (isset($form->registered_user_name)) $search_values['registered_user_name'] = $form->registered_user_name;
        if (isset($form->registered_at_from)) $search_values['registered_at_from'] = $form->registered_at_from;
        if (isset($form->registered_at_to)) $search_values['registered_at_to'] = $form->registered_at_to;
        if (isset($form->fixed_flg)) $search_values['fixed_flg'] = $form->fixed_flg;
        if (isset($form->confirmed_flg)) $search_values['confirmed_flg'] = $form->confirmed_flg;

        return $search_values;
    }


    /**
     * 機器観察研究データ論理削除
     *
     * @param Form\VentilatorValueBulkDeleteForm $form
     * @return type
     */
    public function bulkDelete(Form\VentilatorValueBulkDeleteForm $form, User $user)
    {
        $ids = $form->ids;
        $deletable_row_limit = 50;

        if (count($ids) > $deletable_row_limit) {
            $form->addError('ids', 'validation.excessive_number_of_registrations');
            throw new Exceptions\InvalidFormException($form);
        }

        $operated_user_id = $user->id;
        $organization_id = $user->organization_id;

        // 削除済み、または不正なリクエストidを考慮し、id再取得
        if (Auth\OrgUserGate::canEditAllVentilatorValue($user)) {
            $target_ids = Repos\VentilatorValueRepository::getIdsByOrganizationIdAndIds(
                $organization_id, 
                $ids);
        } else {
            // 全編集権限が無い場合には、登録者idも含めて再取得
            $target_ids = Repos\VentilatorValueRepository::getIdsByOrganizationIdAndRegisteredUserIdAndIds(
                $organization_id,
                $user->id,
                $ids);
        }

        if (!empty($target_ids)) {
            DBUtil::Transaction(
                '機器観察研究データ論理削除、 ヒストリーテーブル登録',
                function () use ($target_ids, $operated_user_id) {
                    // 論理削除
                    Repos\VentilatorValueRepository::logicalDeleteByIds($target_ids->all());

                    // ヒストリーテーブル登録
                    Repos\VentilatorValueHistoryRepository::insertBulk(
                        $target_ids->all(),
                        $operated_user_id,
                        Models\HistoryBaseModel::DELETE
                    );
                }
            );
        }

        return new Response\SuccessJsonResult;
    }
}
