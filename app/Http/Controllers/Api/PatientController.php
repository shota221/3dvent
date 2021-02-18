<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Services\Api as Service;

use Illuminate\Http\Request;

class PatientController extends Controller
{
    private $service;
    
    function __construct() 
    {
        $this->service = new Service\PatientService;
    }

    public function create(Request $request)
    {
        return $this->service->create();
    }

    public function show(Request $request,$id)
    {
        return $this->service->getPatientResult();
    }

    public function update(Request $request,$id)
    {
        return $this->service->update();
    }
}
