<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\StatefulGuard;
use App\Exceptions;
use App\Models;
use App\Repositories as Repos;
use App\Http\Forms as Form;
use App\Http\Response as Response;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Services\Support\Converter;
use App\Services\Support\DBUtil;


/**
 * ユーザー認証サービス
 */
class UserAuthService
{
    public function generateToken($form)
    {
        //単にトークンを発行するのみ
        $organization = Repos\OrganizationRepository::findOneByCode($form->organization_code);

        if (is_null($organization)) {
            $form->addError('organization_code', 'validation.id_not_found');
            return false;
        }

        $credentials = [
            'name' => $form->name,
            'organization_id' => $organization->id,
            'password' => $form->password
        ];

        $userTokenGuard = Auth::guard('user_token');

        if (!$token = $userTokenGuard->regenerateUserToken($credentials)) {
            throw new Exceptions\InvalidException('auth.failed');
        }

        $user = $userTokenGuard->user();

        return Converter\UserConverter::convertToLoginUserResult($user->id, $token, $user->name, $organization->name);
    }

    public function removeToken($user = null)
    {
        $userTokenGuard = Auth::guard('user_token');
        
        return Converter\UserConverter::convertToLogoutUserResult($userTokenGuard->removeUserToken($user));
    }
}
