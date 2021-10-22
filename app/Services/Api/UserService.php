<?php

namespace App\Services\Api;

use App\Exceptions;
use App\Http\Auth;
use App\Repositories as Repos;
use App\Services\Support as Support;
use App\Services\Support\Converter;
use App\Services\Support\DBUtil;

class UserService
{
    public function getUserResult($user)
    {
        $organiztion_name = Repos\OrganizationRepository::findOneById($user->organization_id)->name;

        return Converter\UserConverter::convertToUserResult($user->name, $organiztion_name, $user->email);
    }

    public function update($form, $user)
    {
        if ($form->name !== $user->name) {
            // ユーザー名を変更する場合には組織内で一意であるかどうか確認
            $exists =  Repos\UserRepository::existsByNameAndOrganizationId($form->name, $user->organization_id);

            if ($exists) {
                $form->addError('user_name', 'validation.duplicated_user_name');
                throw new Exceptions\InvalidFormException($form);
            }
        }

        // 権限タイプが医師（施設内研究代表者）だった場合にはメアド必須
        if ($user->org_authority_type === Auth\OrgUserGate::AUTHORITIES['principal_investigator']['type']) {
            if (empty($form->email)) {
                $form->addError('email', 'validation.required_for_principal_investigator');
                throw new Exceptions\InvalidFormException($form);
            }
        }

        // メールの入力がある場合は組織内重複チェック
        if (! empty($form->email)) {
            $registered_user = Repos\UserRepository::findOneByOrganizationIdAndEmail($user->organization_id, $form->email);

            $is_duplicated_email = ! is_null($registered_user) && $registered_user->id !== $user->id;
    
            if ($is_duplicated_email) {
                $form->addError('email', 'validation.duplicated_registration');
                throw new Exceptions\InvalidFormException($form);
            }
        }

        $entity = Converter\UserConverter::convertToUserUpdateEntity($user, $form->name, $user->id, $form->email);

        DBUtil::Transaction(
            'ユーザー情報更新',
            function () use ($entity) {
                $entity->save();
            }
        );

        return Converter\UserConverter::convertToUserUpdateResult($user->name, $user->email);
    }
}
