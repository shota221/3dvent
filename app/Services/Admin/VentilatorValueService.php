<?php

namespace App\Services\Admin;

use App\Exceptions;
use App\Http\Forms\Admin as Form;
use App\Http\Response;
use App\Models;
use App\Repositories as Repos;
use App\Services\Support\Converter;
use App\Services\Support\DBUtil;

class VentilatorValueService
{
    /**
     * 機器観察研究データ一覧取得
     *
     * @param string $path
     * @param Form\VentilatorValueSearchForm $form
     * @return [type]
     */
    public function getPaginatedVentilatorValueData(
        string $path,
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

        $ventilator_value = Repos\VentilatorValueRepository::searchWithUsersAndVentilatorsAndPatientsAndOrganizations(
            $search_values,
            $item_per_page,
            $offset
        );

        $total_count = Repos\VentilatorValueRepository::countBySearchValues($search_values);

        return Converter\VentilatorValueConverter::convertToAdminPagenate(
            $ventilator_value,
            $total_count,
            $item_per_page,
            $path . $http_query
        );
    }

    /**
     * 機器観察研究データ詳細取得
     *
     * @param Form\VentilatorValueEditForm $form
     * @return type
     */
    // public function getOneVentilatorValueData(Form\VentilatorValueEditForm $form)
    // {
    //     $patient_value = Repos\VentilatorValueRepository::findOneWithPatientAndOrganizationById($form->id);

    //     if (is_null($patient_value)) {
    //         $form->addError('id', 'validation.id_not_found');
    //         throw new Exceptions\InvalidFormException($form);
    //     }

    //     return Converter\VentilatorValueConverter::convertToVentilatorValueEditData($patient_value);
    // }

    // function update(Form\VentilatorValueUpdateForm $form)
    // {
    //     $ventilator_value = Repos\VentilatorValueRepository::findOneById($form->id);

    //     $entity = Converter\VentilatorValueConverter::convertToAdminVentilatorValueUpdateEntity($ventilator_value, $form->hoge);

    //     DBUtil::Transaction(
    //         'MicroVent編集',
    //         function () use ($entity) {
    //             $entity->save();
    //         }
    //     );

    //     return new Response\SuccessJsonResult;
    // }


    private function buildVentilatorValueSearchValues(Form\VentilatorValueSearchForm $form)
    {
        $search_values = [];
        if (isset($form->gs1_code)) $search_values['gs1_code'] = $form->gs1_code;
        if (isset($form->organization_id)) $search_values['organization_id'] = $form->organization_id;
        if (isset($form->patient_code)) $search_values['patient_code'] = $form->patient_code;
        if (isset($form->registered_user_name)) $search_values['registered_user_name'] = $form->registered_user_name;
        if (isset($form->registered_at_from)) $search_values['registered_at_from'] = $form->registered_at_from;
        if (isset($form->registered_at_to)) $search_values['registered_at_to'] = $form->registered_at_to;
        if (isset($form->fixed_flg)) $search_values['fixed_flg'] = $form->fixed_flg;
        if (isset($form->confirmed_flg)) $search_values['confirmed_flg'] = $form->confirmed_flg;

        return $search_values;
    }
}
