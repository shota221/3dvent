<?php

namespace App\Services\Admin;

use App\Exceptions;
use App\Http\Forms\Admin as Form;
use App\Http\Response as Response;
use App\Repositories as Repos;
use App\Services\Support\Converter;
use App\Services\Support\CryptUtil;
use App\Services\Support\DateUtil;
use App\Services\Support\DBUtil;
use App\Services\Support\Logic\CsvLogic;
use Illuminate\Database\Eloquent\Collection;

class VentilatorService
{
    use CsvLogic;

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

        $url = !is_null($form) ? $base_url . $form->http_query : $base_url;

        return Converter\VentilatorConverter::convertToAdminPagenate($ventilators, $total_count, $items_per_page, $url);
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

    function getBugsList(Form\VentilatorBugsForm $form)
    {
        $bugs = Repos\VentilatorBugRepository::findByVentilatorId($form->id);

        return array_map(
            function ($user) {
                return Converter\VentilatorConverter::convertToBugsListElmEntity($user);
            },
            $bugs->all()
        );
    }

    function createVentilatorCsv(Form\VentilatorCsvExportForm $form)
    {
        $query = Repos\VentilatorRepository::queryForCreateVentialtorCsvByids($form->ids);

        $filename = config('ventilator_csv.filename');

        $header = config('ventilator_csv.header');

        $this->createSearchDataCsv(
            $filename,
            array_values($header),
            function (Collection $entities){
                return array_map(
                    function ($entity) {
                        return $this->buildVentilatorCsvRow($entity);
                    },
                    $entities->all()
                );
            },
            $query,
        );
    }

    function buildVentilatorCsvRow($entity)
    {
        $row = [];

        $header = config('ventilator_csv.header');
        
        foreach($header as $key=>$val){
            switch($key){
                case 'patient_exists':
                    $row[$key] = intval(!is_null($entity->patient_id));
                    break;
                case 'patient_hash':
                    $row[$key] = !is_null($entity->patient_id) ? CryptUtil::createUniqueToken($entity->patient_id) : null;
                    break;
                case 'patient_value_exists':
                    $row[$key] = intval(!is_null($entity->patient_value_id));
                    break;
                case 'ventilator_value_exists':
                    $row[$key] = intval(!is_null($entity->ventilator_value_id));
                    break;
                default: $row[$key] = $entity->$key;
            }
        }
        return $row;
    }
}
