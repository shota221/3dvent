<?php

namespace App\Http\Response\Api;

use App\Http\Response\JsonResult;

class VentilatorBugListElm extends JsonResult
{   
    public $organization_id;

    public $ventilator_id;

    public $bug_name;
    
    public $request_improvement;

    public $registered_at;
}
