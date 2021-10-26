<?php

namespace App\Services\Org;

use App\Exceptions;
use App\Http\Auth;
use App\Http\Forms\Org as Form;
use App\Http\Response as Response;
use App\Repositories as Repos;
use App\Services\Support\Converter;
use App\Services\Support\DBUtil;
use App\Models\User;
use Exception;

class VentilatorService
{
    public function getVentilatorData($path, User $user, $form = null)
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


        if (Auth\OrgUserGate::canReadAllVentilator($user)) {
            $ventilators = Repos\VentilatorRepository::searchByOrganizationId(
                $user->organization_id, 
                $search_values, 
                $offset, 
                $items_per_page);
    
            $total_count = Repos\VentilatorRepository::countByOrganizationIdAndSearchValues(
                $user->organization_id, 
                $search_values);
        } else {
            $ventilators = Repos\VentilatorRepository::searchByOrganizationIdAndRegisteredUserId(
                $user->organization_id,
                $user->id, 
                $search_values, 
                $offset, 
                $items_per_page);
    
            $total_count = Repos\VentilatorRepository::countByOrganizationIdAndRegisteredUserIdSearchValues(
                $user->organization_id, 
                $user->id,
                $search_values);
        }


        return Converter\VentilatorConverter::convertToOrgPaginate($ventilators, $total_count, $items_per_page, $path . $http_query);
    }

    public function getPatient(Form\VentilatorPatientForm $form, User $user)
    {
        $organization_id = $user->organization_id;

        $patient_code = Repos\VentilatorRepository::getPatientCodeByOrganizationIdAndId($organization_id, $form->id);

        return Converter\VentilatorConverter::convertToPatientResult($patient_code);
    }

    public function update(Form\VentilatorUpdateForm $form, User $user)
    {
        $organization_id = $user->organization_id;

        $ventilator = Repos\VentilatorRepository::findOneByOrganizationIdAndId($organization_id, $form->id);

        if (is_null($ventilator)) {
            $form->addError('id', 'validation.id_not_found');
            throw new Exceptions\InvalidFormException($form);
        }

        // 全編集権限が無い場合には、登録者idが一致しているか確認
        if (! Auth\OrgUserGate::canEditAllVentilator($user)) {
            $is_matched = $ventilator->registered_user_id === $user->id;
            
            if (! $is_matched) {
                $form->addError('id', 'validation.id_not_found');
                throw new Exceptions\InvalidFormException($form);
            }
        }

        $ventilator->start_using_at = $form->start_using_at;

        DBUtil::Transaction(
            'MicroVent編集',
            function () use ($ventilator) {
                $ventilator->save();
            }
        );

        return new Response\SuccessJsonResult;
    }

    private function buildVentilatorSearchValues(Form\VentilatorSearchForm $form)
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

    public function getBugList(Form\VentilatorBugsForm $form, User $user)
    {
        $exists = Repos\VentilatorRepository::existsByOrganizationIdAndId($user->organization_id, $form->id);

        if (!$exists) {
            $form->addError('id', 'validation.id_not_found');
            throw new Exceptions\InvalidFormException($form);
        }

        $bugs = Repos\VentilatorBugRepository::findByVentilatorId($form->id);

        return Converter\VentilatorConverter::convertToBugListData($bugs);
    }
}
