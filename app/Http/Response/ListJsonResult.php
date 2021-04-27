<?php 

namespace App\Http\Response;

class ListJsonResult extends SuccessJsonResult
{
    public $list = [];

    function __construct($data)
    {
        $this->list = $data;
    }
}