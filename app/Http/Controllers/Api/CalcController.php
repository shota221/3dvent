<?php

namespace App\Http\Controllers\Api;

use App\Exceptions;
use App\Http\Controllers\Controller;
use App\Http\Forms\Api as Form;
use App\Services\Api as Service;
use Illuminate\Http\Request;

class CalcController extends Controller
{
    private $service;
    
    function __construct() 
    {
        $this->service = new Service\CalcService;
    }

    public function defaultFlow()
    {
        return $this->service->getDefaultFlow();
    }

    public function estimatedData(Request $request)
    {
        $form = new Form\CalcEstimatedDataForm($request->all());

        if ($form->hasError()) {
            throw new Exceptions\InvalidFormException($form);
        }

        return $this->service->getEstimatedData($form);
    }

    public function ieManual(Request $request)
    {
        $form = new Form\CalcIeManualForm($request->all());

        if ($form->hasError()) {
            throw new Exceptions\InvalidFormException($form);
        }

        return $this->service->getIeManual($form);
    }

    public function ieSound(Request $request)
    {
        $form = new Form\CalcIeSoundForm($request->all());

        if ($form->hasError()) {
            throw new Exceptions\InvalidFormException($form);
        }

        return $this->service->getIeSound($form);
    }

    public function ieSoundSampling(Request $request)
    {
        $form = new Form\CalcIeSoundForm($request->all());

        if ($form->hasError()) {
            throw new Exceptions\InvalidFormException($form);
        }

        return $this->service->putIeSound($form);
    }
}
