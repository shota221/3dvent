<?php

namespace App\Services\Api;

use App\Exceptions;
use App\Models;
use App\Http\Forms\Api as Form;
use App\Http\Response as Response;
use App\Repositories as Repos;
use App\Models\Report;
use App\Services\Support as Support;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
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
        //ユーザー名は組織内にて一意
        if (Repos\UserRepository::existsByNameAndOrganizationId($form->name, $user->organization_id)) {
            $form->addError('user_name', 'validation.duplicated_user_name');
            return false;
        }

        $entity = Converter\UserConverter::convertToUserUpdateEntity($user, $form->name, $form->email, $user->id);

        DBUtil::Transaction(
            'ユーザー情報更新',
            function () use ($entity) {
                $entity->save();
            }
        );

        return Converter\UserConverter::convertToUserUpdateResult($user->name, $user->email);
    }
}
