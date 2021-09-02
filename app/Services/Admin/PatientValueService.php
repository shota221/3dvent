<?php

namespace App\Services\Admin;

use App\Exceptions;
use App\Http\Forms\Admin as Form;
use App\Http\Response;
use App\Models;
use App\Repositories as Repos;
use App\Services\Support\Converter;
use App\Services\Support\DateUtil;
use App\Services\Support\DBUtil;

use Illuminate\Support\Facades\Log;

class PatientValueService
{
    /**
     * 患者観察研究データ一覧取得(ページネーション)
     *
     * @param string $path
     * @param Form\PatientValueSearchForm $form
     * @return [type]
     */
    public function getPaginatedPatientValueData(
        string $path,
        Form\PatientValueSearchForm $form = null)
    {
        $limit = config('view.items_per_page');

        $offset = 0;

        $search_values = [];
        $http_query = '';

        if (! is_null($form)) {
            if (isset($form->page)) $offset = ($form->page -1) * $limit;
            $search_values = $this->buildPatientValueSearchValues($form);
            $http_query = '?' . http_build_query($search_values);
        }

        $patient_values = Repos\PatientValueRepository::findWithPatientAndUserAndOrganizationBySearchValuesAndLimitAndOffsetOrderByCreatedAt(
            $search_values,
            $limit,
            $offset);

        $total_count = Repos\PatientValueRepository::countBySearchValues($search_values);

        $item_per_page = $limit;

        return Converter\PatientValueConverter::convertToPaginatedPatientValueData(
            $patient_values,
            $total_count,
            $item_per_page,
            $path.$http_query
        );
    }

    /**
     * 組織管理者アカウント取得
     *
     * @param Form\PatientValueEditForm $form
     * @return type
     */
    public function getOnePatientValueData(Form\PatientValueEditForm $form)
    {
        $patient_value = Repos\PatientValueRepository::findOneWithPatientAndOrganizationById($form->id);

        if (is_null($patient_value)) {
            $form->addError('id', 'validation.id_not_found');
            throw new Exceptions\InvalidFormException($form);
        }

        return Converter\PatientValueConverter::convertToPatientValueEditData($patient_value);
    }

    /**
     * 組織一覧を取得
     *
     * @return [type]
     */
    public function getOrganizationData()
    {
        $organizations = Repos\OrganizationRepository::findAll();

        return Converter\PatientValueConverter::convertToOrganizationSearchListData($organizations);
    }

    public function update(Form\PatientValueUpdateForm $form)
    {
        // 患者コード重複確認用患者データ格納用
        $confirmation_patient = null;
        
        if (! is_null($form->patient_code)) {
            $confirmation_patient = Repos\PatientRepository::findOneByOrganizationIdAndPatientCode($form->organization_id, $form->patient_code);
        }

        // 編集元データ取得
        $old_patient_value = Repos\PatientValueRepository::findOneById($form->id);

        if (is_null($old_patient_value)) {
            $form->addError('id', 'validation.id_not_found');
            throw new Exceptions\InvalidFormException($form);
        }

        $isDuplicated = ! is_null($confirmation_patient) && $confirmation_patient->id !== $old_patient_value->patient_id;

        if ($isDuplicated) {
            $form->addError('patient_code', 'validation.duplicated_registration');
            throw new Exceptions\InvalidFormException($form);
        }

        $patient = Repos\PatientRepository::findOneById($old_patient_value->patient_id);
        $patient_entity = Converter\PatientConverter::convertToPatientCodeUpdateEntity($patient, $form->patient_code);

        // 編集元データの複製
        $old_patient_value_copy = $old_patient_value->replicate();

        // TODO 認証機能実装後修正
        $user_id = 1;

        // 編集後データ作成
        $new_patient_value = Converter\PatientConverter::convertToPatientValueUpdateEntity(
            $old_patient_value_copy,
            $user_id,
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

        // 編集元データにdeleted_atを記録
        $old_patient_value->deleted_at = DateUtil::toDateTimeStr(DateUtil::now());

        DBUtil::Transaction(
            '編集元データ論理削除、編集後データ登録、ヒストリーテーブル登録、患者コード更新',
            function () use ($old_patient_value, $new_patient_value, $user_id, $patient_entity) {
                // 編集元データ論理削除
                $old_patient_value->save();
                
                //　削除履歴追加
                $delete_history_entity = Converter\HistoryConverter::convertToHistoryEntity(
                    $old_patient_value,
                    Models\HistoryBaseModel::DELETE,
                    $user_id);
                $delete_history_entity->save();

                // 編集後データ登録
                $new_patient_value->save();
                
                //　登録履歴追加
                $create_history_entity = Converter\HistoryConverter::convertToHistoryEntity(
                    $new_patient_value,
                    Models\HistoryBaseModel::CREATE,
                    $user_id);
                $create_history_entity->save();

                // 患者コード更新
                $patient_entity->save();
            }
        );

        return new Response\SuccessJsonResult;
    }


    private function buildPatientValueSearchValues(Form\PatientValueSearchForm $form)
    {
        $search_values = [];
        if (isset($form->organization_id)) $search_values['organization_id'] = $form->organization_id;
        if (isset($form->patient_code)) $search_values['patient_code'] = $form->patient_code;
        if (isset($form->registered_user_name)) $search_values['registered_user_name'] = $form->registered_user_name;
        if (isset($form->registered_at_from)) $search_values['registered_at_from'] = $form->registered_at_from;
        if (isset($form->registered_at_to)) $search_values['registered_at_to'] = $form->registered_at_to;

        return $search_values;
    }
}