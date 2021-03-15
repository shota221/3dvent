<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Services\Api as Service;

use Illuminate\Http\Request;

use App\Http\Forms\Api as Form;

use App\Exceptions;

class CalcController extends Controller
{
    private $service;
    
    function __construct() 
    {
        $this->service = new Service\CalcService;
    }

    public function defaultFlow(Request $request)
    {
        $form = new Form\CalcDefaultFlowForm($request->all());

        if ($form->hasError() || !$response = $this->service->getDefaultFlow($form)) {
            throw new Exceptions\InvalidFormException($form);
        }

        return $response;
    }

    public function estimatedData(Request $request)
    {
        $form = new Form\CalcEstimatedDataForm($request->all());

        if ($form->hasError() || !$response = $this->service->getEstimatedData($form)) {
            throw new Exceptions\InvalidFormException($form);
        }

        return $response;
    }

    public function ieManual(Request $request)
    {
        $form = new Form\CalcIeManualForm($request->all());

        if ($form->hasError() || !$response = $this->service->getIeManual($form)) {
            throw new Exceptions\InvalidFormException($form);
        }

        return $response;
    }

    public function ieSound(Request $request)
    {
        $form = new Form\CalcIeSoundForm($request->all());

        if ($form->hasError() || !$response = $this->service->getIeSound($form)) {
            throw new Exceptions\InvalidFormException($form);
        }

        return $response;
    }
}
