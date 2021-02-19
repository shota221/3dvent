<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Services\Api as Service;

use Illuminate\Http\Request;

class CalcController extends Controller
{
    private $service;
    
    function __construct() 
    {
        $this->service = new Service\CalcService;
    }

    public function defaultFlow(Request $request)
    {
        return $this->service->getDefaultFlow();
    }

    public function estimatedData(Request $request)
    {
        return $this->service->getEstimatedData();
    }

    public function ieManual(Request $request)
    {
        return $this->service->getIeManual();
    }

    public function ieSound(Request $request)
    {
        return $this->service->getIeSound();
    }
}
