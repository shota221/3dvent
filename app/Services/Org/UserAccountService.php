<?php

namespace App\Services\Org;

use App\Exceptions;
use App\Models;
use App\Repositories as Repos;
use App\Http\Forms\Org as Form;
use App\Http\Response as Response;
use App\Services\Support\Converter;
use App\Services\Support\CryptUtil;
use App\Services\Support\DBUtil;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Log;

class UserAccountService
{
    /**
     * 現在のプロフィールを取得
     * 
     * @param  Models\User    $manager [description]
     * @return [type]                  [description]
     */
    public function getProfileData(Models\User $user)
    {
        return Converter\UserResponseConverter::convertToUserProfileResult($user);
    }

    /**
     * プロフィール更新
     * 
     * @param  Form\OrganizationUserProfileForm $form [description]
     * @param  Models\User                      $user [description]
     * @return [type]                                 [description]
     */
    public function updateProfile(Form\OrganizationUserProfileForm $form, Models\User $user)
    {
        $registered_user = Repos\UserRepository::findOneByOrganizationIdAndName($user->organization_id, $form->name);

        $is_duplicated = ! is_null($registered_user) && $registered_user->id !== $user->id;

        if ($is_duplicated) {
            $form->addError('name', 'validation.duplicated_registration');
            throw new Exceptions\InvalidFormException($form);
        }

        $is_principal_investigator = $user->authority_type === Models\User::ORG_PRINCIPAL_INVESTIGATOR_TYPE;
        
        // 組織管理アカウントの場合はメアド必須
        if($is_principal_investigator && empty($form->email)) {
            $form->addError('email', 'validation.required_for_principal_investigator');
            throw new Exceptions\InvalidFormException($form);
        }

        $entity = $user;

        // 更新データのセット
        $entity->updated_user_id = $user->id;
        $entity->name            = $form->name;
        $entity->email           = $form->email;

        if (! is_null($form->password)) $entity->password = CryptUtil::createHashedPassword($form->password);

        DBUtil::Transaction(
            '組織ユーザープロフ更新',
            function () use ($entity) {
                $entity->save();
            }
        );
        
        return new Response\SuccessJsonResult;
    }

   
}


