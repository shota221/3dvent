<?php

namespace App\Http\Response\Api;

use App\Http\Response\JsonResult;

class VentilatorValueElm extends JsonResult
{   
    public $id;

    public $observed_at;

    public $observed_user_name;
}