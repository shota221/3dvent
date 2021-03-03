<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Services\Api as Service;

use Illuminate\Http\Request;

use App\Http\Forms\Api as Form;

use App\Exceptions;

class VentilatorController extends Controller
{
    private $service;
    
    function __construct() 
    {
        $this->service = new Service\VentilatorService;
    }

    public function show(Request $request)
    {
        $form = new Form\VentilatorShowForm($request->all());

        if ($form->hasError() || !$response = $this->service->getVentilatorResult($form)) {
            throw new Exceptions\InvalidFormException($form);
        }

        return $response;
    }

    public function create(Request $request)
    {
        $form = new Form\VentilatorCreateForm($request->all());

        $user_token = $request->hasHeader('X-User-Token') ? $request->header('X-User-Token') : null;

        if ($form->hasError() || !$response = $this->service->create($form,$user_token)) {
            throw new Exceptions\InvalidFormException($form);
        }

        return $response;
    }

    public function showValue(Request $request,$id)
    {
        return $this->service->getVentilatorValue();
    }

    public function createValue(Request $request,$id)
    {
        return $this->service->createVentilatorValue();
    }

    public function updateValue(Request $request,$id)
    {
        return $this->service->updateVentilatorValue();
    }
}
