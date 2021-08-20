<?php

namespace App\Services\Admin;

use App\Exceptions;
use App\Http\Forms\Admin as Form;
use App\Http\Response;
use App\Repositories as Repos;
use App\Services\Support\Converter;
use App\Services\Support\DBUtil;
use Illuminate\Support\Facades\Hash;

class OrganizationAdminUserService
{
    /**
     * 組織管理者アカウント一覧取得(ページネーション)
     *
     * @param string $base_url
     * @param Form\OrganizationAdminUserSearchForm $form 
     * @return [type]
     */
    public function getPaginatedOrganizationAdminUserData(
        string $base_url, 
        Form\OrganizationAdminUserSearchForm $form = null)
    {
        $limit = config('view.items_per_page');

        $offset = 0;

        $search_values = [];
        $http_query = '';

        if (! is_null($form)) {
            if (isset($form->page)) $offset = ($form->page - 1) * $limit;
            $search_values = $this->buildOrganizationAdminUserSearchValues($form);
            $http_query = '?' . http_build_query($search_values);
        }

        // TODO 権限周り決定後修正
        $authority = 1;

        $organization_admin_users = Repos\UserRepository::findWithOrganizationByAuthorityAndSearchValuesAndLimitAndOffsetOrderByCreatedAt(
            $authority, 
            $search_values,
            $limit, 
            $offset);

        $total_count = Repos\UserRepository::countByAuthorityAndSearchValues($authority, $search_values);

        $item_per_page = $limit;

        return Converter\OrganizationAdminUserConverter::convertToPaginatedOrganizationAdminUserData(
            $organization_admin_users,
            $total_count,
            $item_per_page,
            $base_url.$http_query
        );

    }

    /**
     * 組織管理者アカウント取得
     *
     * @param Form\OrganizationAdminUserEditForm $form
     * @return [type]
     */
    public function getOneOrganizationAdminUserData(Form\OrganizationAdminUserEditForm $form)
    {
        // TODO 権限周り決定後修正
        $authority = 1;

        $organization_admin_user = Repos\UserRepository::findOneWithOrganizationByAuthorityAndId($authority, $form->id);

        if (is_null($organization_admin_user)) {
            $form->addError('id', 'validation.id_not_fount');
            throw new Exeptions\InvalidFormException($form);
        }
       
        return Converter\OrganizationAdminUserConverter::convertToOrganizationAdminUserData($organization_admin_user);
    }

    /**
     * 組織管理者アカウント編集
     *
     * @param Form\OrganizationAdminUserUpdateForm $form
     * @return void
     */
    public function update(Form\OrganizationAdminUserUpdateForm $form)
    {
        $organization = Repos\OrganizationRepository::findOneByCode($form->code);

        if (is_null($organization)) {
            $form->addError('code', 'validation.code_not_found');
            throw new Exceptions\InvalidFormException($form);
        } 

        $user = Repos\UserRepository::findOneByOrganizationIdAndName($organization->id, $form->name);

        $isDuplicated = ! is_null($user) && $user->id !== $form->id;

        if ($isDuplicated) {
            $form->addError('name', 'validation.duplicated_registration');
            throw new Exceptions\InvalidFormException($form);
        }

        // TODO 権限周り決定後修正
        $authority = 1;

        $organization_admin_user = Repos\UserRepository::findOneByAuthorityAndId($authority, $form->id);

        if (is_null($organization_admin_user)) {
            $form->addError('id', 'validation.id_not_fount');
            throw new Exeptions\InvalidFormException($form);
        }

        // TODO 認証回り作成後修正
        $updated_user_id = 1;

        $entity = Converter\OrganizationAdminUserConverter::convertToUpdateEntity(
            $organization_admin_user,
            $updated_user_id,
            $form->name,
            $form->email,
            $form->disabled_flg,
            Hash::make($form->password)
        );

        DBUtil::Transaction(
            '組織管理者アカウント編集',
            function () use ($entity) {
                $entity->save();
            }
        );
        
        return new Response\SuccessJsonResult;
    }

    /**
     * 組織管理者アカウント登録
     *
     * @param Form\OrganizationAdminUserCreateForm $form
     * @return void
     */
    public function create(Form\OrganizationAdminUserCreateForm $form)
    {
        $organization = Repos\OrganizationRepository::findOneByCode($form->code);

        if (is_null($organization)) {
            $form->addError('code', 'validation.code_not_found');
            throw new Exceptions\InvalidFormException($form);
        } 

        $exists = Repos\UserRepository::existsByNameAndOrganizationId($form->name, $organization->id);

        if ($exists) {
            $form->addError('name', 'validation.duplicated_registration');
            throw new Exceptions\InvalidFormException($form);
        }

        // TODO 権限周り決定後修正
        $authority = 1;
        // TODO 認証回り作成後修正
        $created_user_id = 1;

        $entity = Converter\OrganizationAdminUserConverter::convertToEntity(
            $authority,
            $created_user_id,
            $organization->id,
            $form->name,
            $form->email,
            Hash::make($form->password),
            $form->disabled_flg
        );

        DBUtil::Transaction(
            '組織管理者アカウント登録',
            function () use ($entity) {
                $entity->save();
            }
        );

        return new Response\SuccessJsonResult;
    }

    /**
     * 組織一覧を取得
     *
     * @return [type]
     */
    public function getOrganizationData()
    {
        $organizations = Repos\OrganizationRepository::findAll();

        return Converter\OrganizationAdminUserConverter::convertToOrganizationSearchListData($organizations);
    }

    private function buildOrganizationAdminUserSearchValues(Form\OrganizationAdminUserSearchForm $form)
    {
        $search_values = [];

        if (isset($form->organization_name)) $search_values['organization_name'] = $form->organization_name;
        if (isset($form->name)) $search_values['name'] = $form->name;
        if (isset($form->registered_at_from)) $search_values['registered_at_from'] = $form->registered_at_from;
        if (isset($form->registered_at_to)) $search_values['registered_at_to'] = $form->registered_at_to;
        if (isset($form->disabled_flg)) $search_values['disabled_flg'] = $form->disabled_flg;

        return $search_values;
    }
}