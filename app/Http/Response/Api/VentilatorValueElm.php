<?php

namespace App\Http\Response\Api;

use App\Http\Response\JsonResult;

class VentilatorValueElm extends JsonResult
{   
    public $id;

    public $registered_at;

    public $registered_user_name;

    public $is_initial;

    public $is_latest;

    public $is_fixed;
}