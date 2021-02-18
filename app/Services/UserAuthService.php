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

/**
 * ユーザー認証サービス
 */
class UserAuthService
{
    public function login()
    {
        return Converter\UserConverter::convertToUserTokenResult();
    }

    public function logout()
    {
        return Converter\UserConverter::convertToUserTokenResult(false);
    }
}


