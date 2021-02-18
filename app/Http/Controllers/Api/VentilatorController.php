<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Services\Api as Service;

use Illuminate\Http\Request;

class VentilatorController extends Controller
{
    private $service;
    
    function __construct() 
    {
        $this->service = new Service\VentilatorService;
    }

    public function show(Request $request)
    {
        return $this->service->getVentilatorResult();
    }

    public function create(Request $request)
    {
        return $this->service->create();
    }

    public function showValues(Request $request,$id)
    {
        return $this->service->getVentilatorValues();
    }

    public function updateValues(Request $request,$id)
    {
        return $this->service->updateVentilatorValues();
    }
}
