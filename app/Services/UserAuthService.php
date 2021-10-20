<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\StatefulGuard;
use App\Exceptions;
use App\Models;
use App\Repositories as Repos;
use App\Http\Forms as Form;
use App\Http\Response as Response;
use App\Services\Support\Converter;
use App\Services\Support\CryptUtil;
use App\Services\Support\DBUtil;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

use Illuminate\Support\Facades\Log;

/**
 * ユーザー認証サービス
 */
class UserAuthService
{
    public function login(Form\UserAuthForm $form, $guard)
    {
        $organization = Repos\OrganizationRepository::findOneByCode($form->organization_code);

        if (is_null($organization)) {
            $form->addError('name', 'validation.account_not_found');
            throw new Exceptions\InvalidFormException($form);
        }

        $credentials = [
            'name'            => $form->name,
            'organization_id' => $organization->id,
            'password'        => $form->password,
            'disabled_flg'    => Models\User::ENABLED,
        ];

        $session_guard = $guard;

        if (! $session_guard->attempt($credentials, $form->remember)) {
            $form->addError('accountOrPassword', 'validation.account_or_password_incorrect');
            throw new Exceptions\InvalidFormException($form);
        }

        // ログイン前アクセスURLにリダイレクト
        $redirect_to = redirect()->intended(guess_route_path('home'))->getTargetUrl();

        return Converter\UserResponseConverter::convertToUserAuthResult($redirect_to);
    }

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

    /**
     * パスワードリセット申請処理
     * 
     * @param  Form\UserApplyPasswordResetForm $form [description]
     * @return [type]                          [description]
     */
    public function applyPasswordReset(Form\UserApplyPasswordResetForm $form, string $user_auth_key)
    {
        $password_reset_token_provider = Password::broker($user_auth_key);

        // メールアドレスチェック
        $user = Repos\UserRepository::findOneWithOrganizationByEmailAndCode($form->email, $form->code);

        if (is_null($user)) {
            $form->addError('email', 'auth.not_exists_registered_email');
            throw new Exceptions\InvalidFormException($form);
        }

        // トークン生成
        $token = $password_reset_token_provider->createToken($user);

        $to = $user->email;

        $subject = '【PRSFRM】パスワード再設定';

        // リセットURLメール通知
        Mail::send(
            [
                'text' => 'Mail.password_reset'
            ], 
            [
                'expire' => config('auth.passwords.' . $user_auth_key . '.expire'),
                'url'    => guess_route('auth.password_reset', ['token' => $token])
            ], 
            function(\Illuminate\Mail\Message $message) use($to, $subject) {
                $message->to($to);

                $message->subject($subject);

                $message->from(
                    'noreply@' . app()->getTld(), 
                    config('app.name') . ' notification'
                );
            }
        );

        return new Response\SuccessJsonResult();
    }

    /**
     * パスワードリセット
     * 
     * @param  Form\UserResetPasswordForm $form [description]
     * @return [type]                           [description]
     */
    public function resetPassword(Form\UserResetPasswordForm $form, string $user_auth_key, $guard)
    {
        $password_reset_token_provider = Password::broker($user_auth_key);

        // メールアドレスチェック
        $user = Repos\UserRepository::findOneWithOrganizationByEmailAndCode($form->email, $form->code);

        if (is_null($user)) {
            $form->addError('email', 'auth.not_exists_registered_email');
            throw new Exceptions\InvalidFormException($form);
        }

        // トークンチェック
        $exists_token = $password_reset_token_provider->tokenExists($user, $form->token);

        if (! $exists_token) {
            $form->addError('email', 'auth.invalid_reset_password_token');
            throw new Exceptions\InvalidFormException($form);
        } 

        // パスワード更新

        $entity = $user;

        $entity->password = Hash::make($form->password);

        $entity->setRememberToken(Str::random(60));

        Support\DBUtil::Transaction(
            'ユーザーパスワードパスワード更新',
            function() use($entity, $password_reset_token_provider) {
                //     
                $entity->save();

                // リセットトークンレコード削除
                $password_reset_token_provider->deleteToken($entity);
            });

        // ログイン
        $guard->login($entity);

        return Converter\UserResponseConverter::convertToPasswordResetResult();
    }
}
