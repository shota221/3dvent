<?php

namespace App\Services\Admin;

use App\Exceptions;
use App\Http\Forms\Admin as Form;
use App\Http\Response;
use App\Repositories as Repos;
use App\Services\Support\Converter;
use App\Services\Support\CryptUtil;
use App\Services\Support\DBUtil;
use Illuminate\Support\Facades\Hash;

class OrganizationAdminUserService
{
    /**
     * 組織管理者アカウント一覧取得(ページネーション)
     *
     * @param string $path
     * @param Form\OrganizationAdminUserSearchForm $form 
     * @return [type]
     */
    public function getPaginatedOrganizationAdminUserData(
        string $path,
        int $authority, 
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

        $organization_admin_users = Repos\UserRepository::searchByAuthority(
            $search_values,
            $authority, 
            $limit, 
            $offset);

        $total_count = Repos\UserRepository::countByAuthorityAndSearchValues($authority, $search_values);

        $item_per_page = $limit;

        return Converter\OrganizationAdminUserConverter::convertToPaginatedOrganizationAdminUserData(
            $organization_admin_users,
            $total_count,
            $item_per_page,
            $path.$http_query
        );

    }

    /**
     * 組織管理者アカウント取得
     *
     * @param Form\OrganizationAdminUserDetailForm $form
     * @return [type]
     */
    public function getOneOrganizationAdminUserData(
        Form\OrganizationAdminUserDetailForm $form,
        int $authority)
    {
        $organization_admin_user = Repos\UserRepository::findOneWithOrganizationByAuthorityAndId($authority, $form->id);

        if (is_null($organization_admin_user)) {
            $form->addError('id', 'validation.id_not_found');
            throw new Exceptions\InvalidFormException($form);
        }
       
        return Converter\OrganizationAdminUserConverter::convertToOrganizationAdminUserData($organization_admin_user);
    }

    /**
     * 組織管理者アカウント編集
     *
     * @param Form\OrganizationAdminUserUpdateForm $form
     * @return void
     */
    public function update(
        Form\OrganizationAdminUserUpdateForm $form,
        int $authority,
        int $user_id)
    {
        $organization = Repos\OrganizationRepository::findOneByCode($form->code);

        if (is_null($organization)) {
            $form->addError('code', 'validation.code_not_found');
            throw new Exceptions\InvalidFormException($form);
        } 

        $user = Repos\UserRepository::findOneByOrganizationIdAndName($organization->id, $form->name);

        $is_duplicated = ! is_null($user) && $user->id !== $form->id;

        if ($is_duplicated) {
            $form->addError('name', 'validation.duplicated_registration');
            throw new Exceptions\InvalidFormException($form);
        }

        $organization_admin_user = Repos\UserRepository::findOneByAuthorityAndId($authority, $form->id);

        if (is_null($organization_admin_user)) {
            $form->addError('id', 'validation.id_not_found');
            throw new Exceptions\InvalidFormException($form);
        }

        // 更新データのセット
        $organization_admin_user->updated_user_id = $user_id;
        $organization_admin_user->name            = $form->name;
        $organization_admin_user->email           = $form->email;
        $organization_admin_user->disabled_flg    = $form->disabled_flg;
        if (! is_null($form->password)) $user->password = CryptUtil::createHashedPassword($form->password);

        DBUtil::Transaction(
            '組織管理者アカウント編集',
            function () use ($organization_admin_user) {
                $organization_admin_user->save();
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
    public function create(
        Form\OrganizationAdminUserCreateForm $form,
        int $authority,
        int $user_id)
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

        $entity = Converter\OrganizationAdminUserConverter::convertToEntity(
            $authority,
            $user_id,
            $organization->id,
            $form->name,
            $form->email,
            CryptUtil::createHashedPassword($form->password),
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

        if (isset($form->organization_id))    $search_values['organization_id']    = $form->organization_id;
        if (isset($form->name))               $search_values['name']               = $form->name;
        if (isset($form->registered_at_from)) $search_values['registered_at_from'] = $form->registered_at_from;
        if (isset($form->registered_at_to))   $search_values['registered_at_to']   = $form->registered_at_to;
        if (isset($form->disabled_flg))       $search_values['disabled_flg']       = $form->disabled_flg;

        return $search_values;
    }
}