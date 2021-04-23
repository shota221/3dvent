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
        $user_token_guard = Auth::guard('user_token');
        //ユーザートークンが付与されていない場合は未ログインユーザーとしてnullを返す。
        return $user_token_guard->user() === get_class($user_token_guard)::NO_TOKEN_USER ? null : $user_token_guard->user();
    }
}
