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
    public function login($form)
    {
        if (app()->isHttpRouteTypeApi()) {
            //単にトークンを発行するのみ
            $organization = Repos\OrganizationRepository::findOneByCode($form->organization_code);

            if (is_null($organization)) {
                $form->addError('organization_code', 'validation.id_not_found');
                return false;
            }

            $user = Repos\UserRepository::findOneByOrganizationIdAndName($organization->id, $form->name);

            if (is_null($user)) {
                $form->addError('name', 'validation.id_not_found');
                return false;
            }

            if (!Hash::check($form->password, $user->password)) {
                throw new Exceptions\InvalidException('auth.failed');
            }

            //X-User-Token発行
            $token = $this->createUniqueToken($user->id);
            $user->api_token = hash('sha256',$token);

            DBUtil::Transaction(
                'api_token生成',
                function()use($user){
                $user->save();
            });

            return Converter\UserConverter::convertToUserResult($user->id,$user->api_token,$user->name,$organization->name);
        }
    }

    public function logout()
    {
        return Converter\UserConverter::convertToUserResult(-1);
    }

    public static function createUniqueToken(string $prefix)
    {
        $prefix = $prefix . mt_rand();

        return sha1(uniqid($prefix, true));
    }
}
