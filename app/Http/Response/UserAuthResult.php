<?php

namespace App\Http\Response;

use App\Http\Response\SuccessJsonResult;

class UserAuthResult extends SuccessJsonResult
{ 
    public $redirect_to = null;   
}