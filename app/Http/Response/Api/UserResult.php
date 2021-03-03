<?php

namespace App\Http\Response\Api;

use App\Http\Response\SuccessJsonResult;

class UserResult extends SuccessJsonResult
{
    public $user_id;

    public $api_token;

    public $user_name;

    public $organization_name;
}
