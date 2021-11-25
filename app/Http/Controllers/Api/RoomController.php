<?php

namespace App\Http\Controllers\Api;

use App\Services\Api as Service;

use Illuminate\Http\Request;

use App\Http\Forms\Api as Form;

use App\Exceptions;

class RoomController extends ApiController
{
    private $service;
    
    function __construct() 
    {
        $this->service = new Service\RoomService;
    }

    public function fetch()
    {
        $appkey = $this->getAppkey();
        
        $response = $this->service->fetch($appkey);

        return $response;
    }
}
