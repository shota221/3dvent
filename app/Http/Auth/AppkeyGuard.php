<?php

namespace App\Http\Auth;

use Illuminate\Http\Request;
use Illuminate\Auth\TokenGuard as BaseTokenGuard;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Carbon;
use Illuminate\Support\HtmlString;

/**
 * USERトークン認証カスタムクラス
 * 
 */
class AppkeyGuard extends BaseTokenGuard
{
    const APPKEY_HEADER = 'X-App-Key';

    /**
     * [__construct description]
     * @param AppkeyProvider $provider  [description]
     * @param Request                     $request   [description]
     * @param array                       $guardConf [description]
     */
    public function __construct(AppkeyProvider $provider, Request $request, array $guardConf)
    {
        // inputKeyが、GET/POSTパラメータから取得するキー.
        
        // storageKeyが、DBのカラム名.

        parent::__construct($provider, $request, $guardConf['input_key'], $guardConf['storage_key']);
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

        $appkey = null;

        $token = $this->getTokenForRequest();

        if (! empty($token)) {
            $appkey = $this->provider->retrieveByAppkey($token);
        }

        return $this->appkey = $appkey;
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
        $token = $this->request->header(self::APPKEY_HEADER, '');

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
     * アプリキー生成
     *
     * @param [type] $idfv
     * @return void
     */
    public function generateAppkey($idfv) 
    {
        return $this->provider->generateAppkey($idfv);
    }
}