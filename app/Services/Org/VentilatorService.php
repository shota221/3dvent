<?php

namespace App\Services\Org;

use App\Exceptions;
use App\Http\Forms\Org as Form;
use App\Http\Response as Response;
use App\Repositories as Repos;
use App\Services\Support\Converter;
use App\Services\Support\DBUtil;

class VentilatorService
{
    function getVentilatorData($path, $form = null)
    {
        $items_per_page = config('view.items_per_page');

        $offset = 0;

        $search_values = [];
        $http_query = '';

        if (!is_null($form)) {

            if (isset($form->page)) $offset = ($form->page - 1) * $items_per_page;

            $search_values = $this->buildVentilatorSearchValues($form);
            $http_query = '?' . http_build_query($search_values);
        }

        //TODO userからorganization_id取得
        $organization_id = 1;

        $ventilators = Repos\VentilatorRepository::findByOrganizationIdAndSearchValuesAndOffsetAndLimit($organization_id, $search_values, $offset, $items_per_page);

        $total_count = Repos\VentilatorRepository::countByOrganizationIdAndSearchValues($organization_id, $search_values);

        return Converter\VentilatorConverter::convertToOrgPaginate($ventilators, $total_count, $items_per_page, $path . $http_query);
    }

    function getPatient(Form\VentilatorPatientForm $form)
    {
        //TODO 認証ユーザーからの取り出し
        $organization_id = 1;

        $patient_code = Repos\VentilatorRepository::getPatientCodeByOrganizationIdAndId($organization_id, $form->id);

        return Converter\VentilatorConverter::convertToPatientResult($patient_code);
    }

    function update(Form\VentilatorUpdateForm $form)
    {
        //TODO 認証ユーザーからの取り出し
        $organization_id = 1;

        $ventilator = Repos\VentilatorRepository::findOneByOrganizationIdAndId($organization_id, $form->id);

        $entity = Converter\VentilatorConverter::convertToOrgVentilatorUpdateEntity($ventilator, $form->start_using_at);

        DBUtil::Transaction(
            'MicroVent編集',
            function () use ($entity) {
                $entity->save();
            }
        );

        return new Response\SuccessJsonResult;
    }

    function bulkDelete(Form\VentilatorBulkDeleteForm $form)
    {
        $ids = $form->ids;
        $deletable_row_limit = config('view.items_per_page');

        // ページネーションで表示する件数より多い場合は例外処理
        if (count($ids) > $deletable_row_limit) {
            $form->addError('validation.excessive_number_of_registrations');
            throw new Exceptions\InvalidFormException($form);
        }

        //TODO 認証ユーザーからの取り出し
        $organization_id = 1;

        DBUtil::Transaction(
            'MicroVent削除',
            function () use ($organization_id, $ids) {
                Repos\VentilatorRepository::logicalDeleteByOrganizationIdAndIds($organization_id, $ids);
            }
        );

        return new Response\SuccessJsonResult;
    }

    function buildVentilatorSearchValues(Form\VentilatorSearchForm $form)
    {
        $search_values = [];

        if (isset($form->serial_number)) $search_values['serial_number'] = $form->serial_number;
        if (isset($form->registered_user_name)) $search_values['registered_user_name'] = $form->registered_user_name;
        if (isset($form->expiration_date_from)) $search_values['expiration_date_from'] = $form->expiration_date_from;
        if (isset($form->expiration_date_to)) $search_values['expiration_date_to'] = $form->expiration_date_to;
        if (isset($form->start_using_at_from)) $search_values['start_using_at_from'] = $form->start_using_at_from;
        if (isset($form->start_using_at_to)) $search_values['start_using_at_to'] = $form->start_using_at_to;
        if (isset($form->has_bug)) $search_values['has_bug'] = $form->has_bug;

        return $search_values;
    }

    function getBugList(Form\VentilatorBugsForm $form)
    {
        $bugs = Repos\VentilatorBugRepository::findByVentilatorId($form->id);

        return Converter\VentilatorConverter::convertToBugListData($bugs);
    }
}
