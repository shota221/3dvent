<?php

namespace App\Services\Admin;

use App\Exceptions;
use App\Http\Forms\Admin as Form;
use App\Http\Response as Response;
use App\Repositories as Repos;
use App\Services\Support\Converter;
use App\Services\Support\DateUtil;
use App\Services\Support\DBUtil;
use Illuminate\Support\Facades\Date;
use Psy\Formatter\Formatter;

class VentilatorService
{
    function getVentilatorData($base_url, $form = null)
    {
        $items_per_page = config('view.items_per_page');

        $offset = 0;

        $search_values = [];

        if (!is_null($form)) {

            if (isset($form->page)) $offset = ($form->page - 1) * $items_per_page;

            $search_values = $this->buildVentilatorSearchValues($form);
        }

        $ventilators = Repos\VentilatorRepository::findBySearchValuesAndOffsetAndLimit($offset, $items_per_page, $search_values);

        $total_count = Repos\VentilatorRepository::countBySearchValues($search_values);

        return Converter\VentilatorConverter::convertToAdminPagenate($ventilators, $total_count, $items_per_page, $base_url);
    }

    function getPatient(Form\VentilatorPatientForm $form)
    {
        $patient_code = Repos\VentilatorRepository::getPatientCodeById($form->id);

        return Converter\VentilatorConverter::convertToPatientResult($patient_code);
    }

    function update(Form\VentilatorUpdateForm $form)
    {
        $ventilator = Repos\VentilatorRepository::findOneById($form->id);

        $entity = Converter\VentilatorConverter::convertToAdminVentilatorUpdateEntity($ventilator, $form->start_using_at);

        DBUtil::Transaction(
            'MicroVent編集',
            function () use ($entity) {
                $entity->save();
            }
        );

        return new Response\SuccessJsonResult;
    }

    function delete(Form\VentilatorDeleteForm $form)
    {
        $ids = $form->ids;

        DBUtil::Transaction(
            'MicroVent削除',
            function () use ($ids) {
                Repos\VentilatorRepository::deleteByIds($ids);
            }
        );

        return new Response\SuccessJsonResult;
    }

    function buildVentilatorSearchValues(Form\VentilatorSearchForm $form)
    {
        $search_values = [];

        if (isset($form->serial_number)) $search_values['serial_number'] = $form->serial_number;
        if (isset($form->organization_name)) $search_values['organization_name'] = $form->organization_name;
        if (isset($form->registered_user_name)) $search_values['registered_user_name'] = $form->registered_user_name;
        if (isset($form->expiration_date_from)) $search_values['expiration_date_from'] = $form->expiration_date_from;
        if (isset($form->expiration_date_to)) $search_values['expiration_date_to'] = $form->expiration_date_to;
        if (isset($form->start_using_at_from)) $search_values['start_using_at_from'] = $form->start_using_at_from;
        if (isset($form->start_using_at_to)) $search_values['start_using_at_to'] = $form->start_using_at_to;
        if (isset($form->has_bug)) $search_values['has_bug'] = $form->has_bug;

        return $search_values;
    }
}
