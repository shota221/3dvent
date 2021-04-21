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
        $user_id = null;

        if (!is_null($user)) {
            $user_id = $user->id;
            $user->api_token = '';

            DBUtil::Transaction(
                'api_token削除',
                function () use ($user) {
                    $user->save();
                }
            );
        }

        return Converter\UserConverter::convertToLogoutUserResult($user_id);
    }

    public static function createUniqueToken(string $prefix)
    {
        $prefix = $prefix . mt_rand();

        return sha1(uniqid($prefix, true));
    }
}
