<?php

namespace App\Http\Response\Org;

use App\Http\Response\SuccessJsonResult;

class UserData extends SuccessJsonResult 
{
    public $id;
    public $name;
    public $authority_name;
    public $authority_type;
    public $email;
    public $created_at;
    public $disabled_flg;
}