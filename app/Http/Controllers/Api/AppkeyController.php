<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Services\Api as Service;

use Illuminate\Http\Request;

class AppkeyController extends Controller
{
    private $service;
    
    function __construct() 
    {
        $this->service = new Service\AppkeyService;
    }

    public function create(Request $request)
    {
        return $this->service->create();
    }
}
