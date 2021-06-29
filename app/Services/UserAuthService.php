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
use App\Services\Support\CryptUtil;
use App\Services\Support\DBUtil;
use Illuminate\Contracts\Auth\Authenticatable;

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
            $form->addError('organization_code', 'validation.account_not_found');
            return false;
        }

        $credentials = [
            'name' => $form->name,
            'organization_id' => $organization->id,
            'password' => $form->password
        ];

        $userGuard = Auth::guard('user');

        if (!$userGuard->attempt($credentials)) {
            throw new Exceptions\InvalidException('auth.failed');
        }

        $user = $userGuard->user();

        $token = $this->regenerateToken($user,true);

        return Converter\UserConverter::convertToLoginUserResult($user->id, $token, $user->name, $organization->name);
    }

    public function removeToken($user = null)
    {
        $token_removed_user_id = null;

        if (!is_null($user)) {
            $user->api_token = '';

            DBUtil::Transaction(
                'ユーザートークン失効',
                function () use ($user) {
                    $user->save();
                }
            );

            $token_removed_user_id = $user->id;
        }

        return Converter\UserConverter::convertToLogoutUserResult($token_removed_user_id);
    }

    public function checkHasToken($form)
    {
        $organization = Repos\OrganizationRepository::findOneByCode($form->organization_code);

        if (is_null($organization)) {
            $form->addError('organization_code', 'validation.account_not_found');
            return false;
        }

        $user = Repos\UserRepository::findOneByOrganizationIdAndName($organization->id, $form->name);

        return Converter\UserConverter::convertToCheckHasTokenResult(!is_null($user) && !empty($user->api_token));
    }

        /** 
     * トークン再生成
     * 
     * @param   $user [description]
     * @param  bool   $hash        [description]
     * @return string              [description]
     */
    public function regenerateToken($user, bool $hash)
    {
        $token = CryptUtil::createUniqueToken($user->id);

        $user->api_token = CryptUtil::createTokenForStorage($token, $hash);

        DBUtil::Transaction(
            'ユーザートークン発行',
            function () use ($user) {
                $user->save();
            }
        );
        
        return $token;
    }
}
