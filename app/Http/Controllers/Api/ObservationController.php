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

    public function count(Request $request)
    {
        $form = new Form\ObservationCountForm($request->all());

        if ($form->hasError() || !$response = $this->service->count($form)) {
            throw new Exceptions\InvalidFormException($form);
        }

        return $response;
    }

    public function patientList(Request $request) 
    {        
        $form = new Form\PatientObservationListForm($request->all());

        if ($form->hasError() || !$response = $this->service->getPatientList($form)) {
            throw new Exceptions\InvalidFormException($form);
        }

        return $response;
    }


    public function ventilatorList(Request $request) 
    {
        $form = new Form\VentilatorObservationListForm($request->all());

        if ($form->hasError() || !$response = $this->service->getVentilatorList($form)) {
            throw new Exceptions\InvalidFormException($form);
        }

        return $response;
    }

    public function ventilatorBugList(Request $request) 
    {
        $form = new Form\VentilatorBugListForm($request->all());

        if ($form->hasError() || !$response = $this->service->getVentilatorBugList($form)) {
            throw new Exceptions\InvalidFormException($form);
        }

        return $response;
    }
}
