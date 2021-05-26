<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Services\Api as Service;

use Illuminate\Http\Request;

use App\Http\Forms\Api as Form;

use App\Exceptions;

class ObservationController extends Controller
{
    private $service;
    
    function __construct() 
    {
        $this->service = new Service\ObservationService;
    }

    // TODO 以下補完作業

    public function count(Request $request)
    {
         $response = $this->service->count();

        return $response;
    }

    public function patientList(Request $request) 
    {
        $response = $this->service->getPatientList();

        return $response;
    }


    public function ventilatorList(Request $request) 
    {
        $response = $this->service->getVentilatorList();

        return $response;
    }

    
    public function ventilatorBugList(Request $request) 
    {
        $response = $this->service->getVentilatorBugList();

        return $response;
    }
}
