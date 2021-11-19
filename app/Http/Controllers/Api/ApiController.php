<?php

namespace App\Http\Controllers\Api;

use App\Http\Auth\AppkeyGate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller as BaseController;

abstract class ApiController extends BaseController
{
    /**
     * user tokenがあればそこからユーザーを取得
     * 
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    protected function getUser()
    {
        return Auth::guard('user_token')->user();
    }

    protected function getAppkey()
    {
        return AppkeyGate::getValidAppkey();
    }
}
