<?php

namespace App\Services\Org;

use App\Exceptions;
use App\Http\Forms\Org as Form;
use App\Http\Response;
use App\Repositories as Repos;
use App\Services\Support\Converter;
use App\Services\Support\DBUtil;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Log;

class UserService
{
    /**
     * ユーザー一覧取得(ページネーション)
     *
     * @param              string $path
     * @param Form\UserSearchForm $form
     * @return [type]
     */
    public function getPaginatedUserData(
        string $path,
        int $organization_id,
        Form\UserSearchForm $form = null)
    {
        $limit         = config('view.items_per_page');
        $offset        = 0;
        $search_values = [];
        $http_query    = '';

        if (! is_null($form)) {
            if (isset($form->page)) $offset = ($form->page - 1) * $limit;
            $search_values = $this->buildUserSearchValues($form);
            $http_query = '?' . http_build_query($search_values);
        }
        
        $users = Repos\UserRepository::searchByOrganizationId(
            $search_values,
            $organization_id, 
            $limit, 
            $offset);

        $total_count = Repos\UserRepository::countByOrganizationIdAndSearchValues($organization_id, $search_values);
        
        $item_per_page = $limit;

        return Converter\UserConverter::convertToPaginatedUserData(
            $users, 
            $total_count, 
            $item_per_page, 
            $path . $http_query);
    }

    /**
     * ユーザーアカウント取得
     *
     * @param Form\UserDetailForm $form
     * @return type
     */
    public function getOneUserData(
        Form\UserDetailForm $form, 
        int $organization_id)
    {
        // ログインユーザーの所属組織に属するユーザー取得
        $user = Repos\UserRepository::findOneByOrganizationIdAndId($organization_id, $form->id);

        if (is_null($user)) {
            $form->addError('id', 'validation.id_not_found');
            throw new Exceptions\InvalidFormException($form);
        }

        return Converter\UserConverter::convertToUserData($user);
    }

    /**
     * ユーザーアカウント修正
     *
     * @param Form\UserUpdateForm $form
     * @return type
     */
    public function update(
        Form\UserUpdateForm $form, 
        int $organization_id, 
        int $user_id)
    {
        // ログインユーザーの所属組織に属するユーザー取得
        $user = Repos\UserRepository::findOneByOrganizationIdAndId($organization_id, $form->id);

        if (is_null($user)) {
            $form->addError('id', 'validation.id_not_found');
            throw new Exceptions\InvalidFormException($form);
        }

        // 組織内ユーザー名重複確認用ユーザーデータ格納
        $confirmation_user = Repos\UserRepository::findOnebyOrganizationIdAndName($organization_id, $form->name);

        $is_duplicated = ! is_null($confirmation_user) && $confirmation_user->id !== $user->id;

        if ($is_duplicated) {
            $form->addError('name', 'validation.duplicated_registration');
            throw new Exceptions\InvalidFormException($form);
        }
     
        // 更新データのセット
        $user->updated_user_id = $user_id;
        $user->name            = $form->name;
        $user->email           = $form->email;
        $user->authority       = $form->authority;
        $user->disabled_flg    = $form->disabled_flg;
        if (! is_null($form->password)) $user->password = Hash::make($form->password);
        
        DBUtil::Transaction(
            'ユーザー編集',
            function () use ($user) {
                $user->save();
            }
        );
        
        return new Response\SuccessJsonResult;
    }

    /**
     * ユーザーアカウント登録
     *
     * @param Form\UserCreateForm $form
     * @return type
     */
    public function create(
        Form\UserCreateForm $form, 
        int $organization_id, 
        int $user_id)
    {
        $exists = Repos\UserRepository::existsByNameAndOrganizationId($form->name, $organization_id);

        if ($exists) {
            $form->addError('name', 'validation.duplicated_registration');
            throw new Exceptions\InvalidFormException($form);
        }

        $entity = Converter\UserConverter::convertToEntity(
            $organization_id,
            $user_id,
            $form->name,
            $form->email,
            $form->authority,
            $form->disabled_flg,
            Hash::make($form->password));

        
        DBUtil::Transaction(
            'ユーザーアカウント登録',
            function () use ($entity) {
                $entity->save();
            }
        );

        return new Response\SuccessJsonResult;
    }

    /**
     * ユーザー論理削除
     *
     * @param Form\logicalDelete $form
     * @return void
     */
    public function logicalDelete(
        Form\UserLogicalDeleteForm $form, 
        int $organization_id, 
        int $user_id)
    {
        $ids = $form->ids;

        $deletable_row_limit = 50; // ページネーション表示件数

        if (count($ids) > $deletable_row_limit) {
            throw new Exceptions\InvalidFormException('validation.excessive_number_of_registrations');
        }

        // リクエストのidが組織齟齬の可能性があるためid再取得
        $target_ids = Repos\UserRepository::getIdsByOrganizationIdAndIds($organization_id, $ids);

        if (is_null($target_ids)) {
            $form->addError('id', 'validation.id_not_found');
            throw new Exceptions\InvalidFormException($form);
        }

        DBUtil::Transaction(
            'ユーザー論理削除',
            function () use ($target_ids, $user_id) {
                Repos\UserRepository::logicalDeleteByIds($target_ids->all(), $user_id);
            }
        );

        return new Response\SuccessJsonResult;
    }

    private function buildUserSearchValues(Form\UserSearchForm $form)
    {
        $search_values      = [];
        $name               = $form->name;
        $authority          = $form->authority;
        $registered_at_from = $form->registered_at_from;
        $registered_at_to   = $form->registered_at_to;
        $disabled_flg       = $form->disabled_flg;
        
        if (isset($name))               $search_values['name']               = $name;
        if (isset($authority))          $search_values['authority']          = $authority;
        if (isset($registered_at_from)) $search_values['registered_at_from'] = $registered_at_from;
        if (isset($registered_at_to))   $search_values['registered_at_to']   = $registered_at_to;
        if (isset($disabled_flg))       $search_values['disabled_flg']       = $disabled_flg;

        return $search_values;
    }
}