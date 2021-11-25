<?php

namespace App\Http\Controllers\Api;

use App\Services\Api as Service;

class RoomController extends ApiController
{
    private $service;
    
    function __construct() 
    {
        $this->service = new Service\RoomService;
    }

    public function fetchRoomUri()
    {
        $appkey = $this->getAppkey();
        
        $response = $this->service->fetchRoomUri($appkey);

        return $response;
    }
}
