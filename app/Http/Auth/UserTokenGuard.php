<?php

namespace App\Http\Auth;

use Illuminate\Http\Request;
use Illuminate\Auth\TokenGuard as BaseTokenGuard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Carbon;
use Illuminate\Support\HtmlString;

/**
 * USERトークン認証カスタムクラス
 * 
 */
class UserTokenGuard extends BaseTokenGuard
{
    const USER_TOKEN_HEADER = 'X-User-Token';

    /**
     * [__construct description]
     * @param UserProvider $provider  [description]
     * @param Request                     $request   [description]
     * @param array                       $guardConf [description]
     */
    public function __construct(UserProvider $provider, Request $request, array $guardConf)
    {
        // inputKeyが、GET/POSTパラメータから取得するキー.
        
        // storageKeyが、DBのカラム名.

        parent::__construct($provider, $request, $guardConf['input_key'], $guardConf['storage_key'], $guardConf['hash']);
    }

    /**
     * @override
     * 
     * 認証
     * 
     * @return [type] [description]
     */
    public function user()
    {
        if (! is_null($this->user)) {
            return $this->user;
        }

        $user = null;

        $token = $this->getTokenForRequest();

        if (! empty($token)) {
            $user = $this->provider->retrieveByUserToken($token, $this->hash);
        }

        return $this->user = $user;
    }

    /**
     * @override
     * 
     * Get the token for the current request.
     *
     * @return string
     */
    public function getTokenForRequest()
    {
        $token = $this->request->header(self::USER_TOKEN_HEADER, '');

        if (! is_null($this->inputKey)) {
            if (empty($token)) {
                // GET
                $token = $this->request->query($this->inputKey);
            }
            if (empty($token)) {
                // POST
                $token = $this->request->input($this->inputKey);
            }
        }

        return $token;
    }

    /**
     * トークン再生成
     * 
     * @param  array  $credentials [description]
     * @return [type]              [description]
     */
    public function regenerateUserToken(array $credentials) 
    {
        $user = $this->provider->retrieveByCredentials($credentials);

        if (! $user || ! $this->provider->validateCredentials($user, $credentials)) {
            return null;
        }

        $this->user = $user;

        return $this->provider->regenerateToken($user, $this->hash);
    }
}