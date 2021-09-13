<?php

namespace App\Services\Admin;

use App\Exceptions;
use App\Http\Forms\Admin as Form;
use App\Http\Response as Response;
use App\Repositories as Repos;
use App\Services\Support\Converter;
use App\Services\Support\DBUtil;

class OrganizationService
{
    function getOrganizationData($path, $form = null)
    {
        $items_per_page = config('view.items_per_page');

        $offset = 0;

        $search_values = [];
        $http_query = '';

        if (!is_null($form)) {

            if (isset($form->page)) $offset = ($form->page - 1) * $items_per_page;

            $search_values = $this->buildOrganizationSearchValues($form);
            $http_query = '?' . http_build_query($search_values);
        }

        $organizations = Repos\OrganizationRepository::findBySearchValuesAndOffsetAndLimit($offset, $items_per_page, $search_values);

        $total_count = Repos\OrganizationRepository::countBySearchValues($search_values);

        return Converter\OrganizationConverter::convertToPaginate($organizations, $total_count, $items_per_page, $path . $http_query);
    }

    function create($form)
    {
        $exists_by_code = Repos\OrganizationRepository::existsByCode($form->organization_code);

        if ($exists_by_code) {
            $form->addError('organization_code', 'validation.duplicated_registration');
        }

        $exists_by_representative_email = Repos\OrganizationRepository::existsByRepresentativeEmail($form->representative_email);

        if ($exists_by_representative_email) {
            $form->addError('representative_email', 'validation.duplicated_registration');
        }

        if ($form->hasError()) {
            throw new Exceptions\InvalidFormException($form);
        }

        $organization = Converter\OrganizationConverter::convertToEntity(
            $form->organization_name,
            $form->organization_code,
            $form->representative_name,
            $form->representative_email,
            $form->disabled_flg,
            $form->patient_obs_approved_flg,
            $form->edcid
        );

        DBUtil::Transaction(
            '組織登録',
            function () use ($organization) {
                $organization->save();
            }
        );

        return new Response\SuccessJsonResult;
    }


    function update($form)
    {
        $organization = Repos\OrganizationRepository::findOneById($form->id);

        $isRegisteredOrganization = !is_null($organization);

        if (!$isRegisteredOrganization) {
            $form->addError('id', 'validation.id_not_found');
            throw new Exceptions\InvalidFormException($form);
        }

        //組織コード重複チェック
        $is_match_organization_code = $form->organization_code === $organization->code;

        $exists_by_code = !$is_match_organization_code && Repos\OrganizationRepository::existsByCode($form->organization_code);

        if ($exists_by_code) {
            $form->addError('organization_code', 'validation.duplicated_registration');
        }

        //メールアドレス重複チェック
        $is_match_representative_email = $form->representative_email === $organization->representative_email;

        $exists_by_representative_email = !$is_match_representative_email && Repos\OrganizationRepository::existsByRepresentativeEmail($form->representative_email);

        if ($exists_by_representative_email) {
            $form->addError('representative_email', 'validation.duplicated_registration');
        }

        if ($form->hasError()) {
            throw new Exceptions\InvalidFormException($form);
        }

        $entity = Converter\OrganizationConverter::convertToUpdateEntity(
            $organization,
            $form->organization_name,
            $form->organization_code,
            $form->representative_name,
            $form->representative_email,
            $form->disabled_flg,
            $form->patient_obs_approved_flg,
            $form->edcid
        );

        DBUtil::Transaction(
            '組織編集',
            function () use ($entity) {
                $entity->save();
            }
        );

        return new Response\SuccessJsonResult;
    }

    function buildOrganizationSearchValues(Form\OrganizationSearchForm $form)
    {
        $search_values = [];

        if (isset($form->organization_name)) $search_values['organization_name'] = $form->organization_name;
        if (isset($form->representative_name)) $search_values['representative_name'] = $form->representative_name;
        if (isset($form->organization_code)) $search_values['organization_code'] = $form->organization_code;
        if (isset($form->disabled_flg)) $search_values['disabled_flg'] = $form->disabled_flg;
        if (isset($form->edc_linked_flg)) $search_values['edc_linked_flg'] = $form->edc_linked_flg;
        if (isset($form->patient_obs_approved_flg)) $search_values['patient_obs_approved_flg'] = $form->patient_obs_approved_flg;
        if (isset($form->registered_at_from)) $search_values['registered_at_from'] = $form->registered_at_from;
        if (isset($form->registered_at_to)) $search_values['registered_at_to'] = $form->registered_at_to;

        return $search_values;
    }

    function getUsersList(Form\OrganizationUsersForm $form)
    {
        $users = Repos\UserRepository::findByOrganizationId($form->id);

        return Converter\OrganizationConverter::convertToUsersList($users);
    }

    /**
     * 組織一覧を取得
     *
     * @return [type]
     */
    public function getSearchList()
    {
        $organizations = Repos\OrganizationRepository::findAll();

        return Converter\OrganizationConverter::convertToOrganizationSearchList($organizations);
    }
}
