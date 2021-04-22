<?php 

namespace App\Http\Response;

class ListJsonResult extends JsonResult
{
    public $list = [];
    
    function __construct(array $data)
    {
        $this->list = $data;
    }
    
    public function jsonSerialize()
    {
        return ['result' => $this->list];
    }
}