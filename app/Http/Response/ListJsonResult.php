<?php 

namespace App\Http\Response;

class ListJsonResult extends JsonResult
{
    public $result = [];

    function __construct($data)
    {
        $this->result = $data;
    }
}