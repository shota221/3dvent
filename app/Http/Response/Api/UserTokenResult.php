<?php

namespace App\Http\Response;

use App\Http\Response\SuccessJsonResult;

class UserTokenResult extends SuccessJsonResult
{
    public $user_id;

    public $api_token;
}