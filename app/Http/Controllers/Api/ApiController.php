<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller as BaseController;

abstract class ApiController extends BaseController
{
    /**
     * user tokenがあればそこからユーザーを取得
     * 
     * @return [type] [description]
     */
    protected function getUser()
    {
        return Auth::guard('user_token')->user();
    }

    protected function getAppkey()
    {
        return Auth::guard('appkey')->user();
    }
}
