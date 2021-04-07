<?php

namespace App\Http\Auth;

use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Authenticatable;

/**
 * APIトークン認証カスタムクラス
 * 
 */
class ApiTokenGuard implements Guard
{
    const API_TOKEN_HEADER = 'X-Api-Token';

    private $request;

    private $secretToken;

    private $inputKey;

    /**
     * [__construct description]
     * @param UserProvider $provider  [description]
     * @param Request                     $request   [description]
     * @param array                       $guardConf [description]
     */
    public function __construct(Request $request, array $guardConf)
    {
        $this->request = $request;
        
        // inputKeyが、GET/POSTパラメータから取得するキー.
        
        $this->inputKey = $guardConf['input_key'];

        $this->secretToken = $guardConf['secret_token'];
    }

    /**
     * @override
     * 
     * Get the token for the current request.
     *
     * @return string
     */
    private function getApiTokenForRequest()
    {
        $token = $this->request->header(self::API_TOKEN_HEADER);

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

    /*************
     * Implements
     *************/

    /**
     * @override
     * 
     * 静的トークンにて認証
     */
    public function check()
    {
        // secret token 取得
        $secretToken = $this->getApiTokenForRequest();

        if ($secretToken === $this->secretToken) {
            return true;
        }

        return false;
    }

    /**
     * @override
     * 
     * Determine if the current user is a guest.
     *
     * @return bool
     */
    public function guest() 
    {
        return ! $this->check();
    }

    /**
     * @override
     * 
     * Get the currently authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function user()
    {
        return null;
    }

    /**
     * @override
     * 
     * Get the ID for the currently authenticated user.
     *
     * @return int|null
     */
    public function id()
    {
        return null;
    }

    /**
     * @override
     * 
     * Validate a user's credentials.
     *
     * @param  array  $credentials
     * @return bool
     */
    public function validate(array $credentials = [])
    {
        return false;
    }

    /**
     * @override
     * 
     * Set the current user.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return void
     */
    public function setUser(Authenticatable $user)
    {
    }

    
}