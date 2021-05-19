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
        //フォームとアップデート先両方にユーザ名があり、それらが同一でないかつ、同一組織内に同じユーザ名が存在するかどうか
        $exists =  !is_null($form->name) && !is_null($user->name) && $form->name !== $user->name && Repos\UserRepository::existsByNameAndOrganizationId($form->name, $user->organization_id);
        //ユーザー名は組織内にて一意
        if ($exists) {
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
