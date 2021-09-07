<?php

namespace App\Services\Org;

use App\Exceptions;
use App\Http\Forms\Org as Form;
use App\Http\Response;
use App\Models;
use App\Repositories as Repos;
use App\Services\Support\Converter;
use App\Services\Support\DateUtil;
use App\Services\Support\DBUtil;

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
     * 患者観察研究データ取得
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

        // 取得組織とユーザーの所属組織の齟齬確認
        $is_Matched = $patient_value->organization_id ===  1; // TODO　認証機能実装後修正 
        
        if (! $is_Matched) {
            $form->addError('id', 'validation.id_not_found');
            throw new Exceptions\InvalidFormException($form);
        }

        return Converter\PatientValueConverter::convertToPatientValueEditData($patient_value);
    }

    private function buildPatientValueSearchValues(Form\PatientValueSearchForm $form)
    {
        $search_values = [];
        $search_values['organization_id'] = 1; // TODO　認証機能実装後修正
        if (isset($form->patient_code)) $search_values['patient_code'] = $form->patient_code;
        if (isset($form->registered_user_name)) $search_values['registered_user_name'] = $form->registered_user_name;
        if (isset($form->registered_at_from)) $search_values['registered_at_from'] = $form->registered_at_from;
        if (isset($form->registered_at_to)) $search_values['registered_at_to'] = $form->registered_at_to;

        return $search_values;
    }

}