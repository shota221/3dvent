<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Services\Api as Service;

use Illuminate\Http\Request;

use App\Http\Forms\Api as Form;

use App\Exceptions;

class PatientController extends ApiController
{
    private $service;

    function __construct()
    {
        $this->service = new Service\PatientService;
    }

    public function create(Request $request)
    {
        $form = new Form\PatientCreateForm($request->all());

        $user = $this->getUser();

        if ($form->hasError()) {
            throw new Exceptions\InvalidFormException($form);
        }
        
        $response = $this->service->create($form, $user);

        return $response;
    }

    public function show(Request $request, $id)
    {
        $request->merge(['id' => $id]);

        $form = new Form\PatientShowForm($request->all());

        if ($form->hasError()) {
            throw new Exceptions\InvalidFormException($form);
        }

        $response = $this->service->getPatientResult($form);

        return $response;
    }

    public function update(Request $request, $id)
    {
        $request->merge(['id' => $id]);

        $form = new Form\PatientUpdateForm($request->all());

        if ($form->hasError()) {
            throw new Exceptions\InvalidFormException($form);
        }
        
        $response = $this->service->update($form);

        return $response;
    }

    public function showValue(Request $request, $id)
    {
        $request->merge(['id' => $id]);

        $form = new Form\PatientShowForm($request->all());

        $user = $this->getUser();

        if ($form->hasError()) {
            throw new Exceptions\InvalidFormException($form);
        }
        
        $response = $this->service->getPatientValueResult($form, $user);

        return $response;
    }

    public function createValue(Request $request, $id)
    {
        $request->merge(['id' => $id]);

        $form = new Form\PatientValueForm($request->all());

        $user = $this->getUser();

        if ($form->hasError()) {
            throw new Exceptions\InvalidFormException($form);
        }
        
        $response = $this->service->createPatientValue($form, $user);

        return $response;
    }

    public function updateValue(Request $request, $id)
    {
        $request->merge(['id' => $id]);

        $form = new Form\PatientValueForm($request->all());

        $user = $this->getUser();

        if ($form->hasError()) {
            throw new Exceptions\InvalidFormException($form);
        }
        
        $response = $this->service->updatePatientValue($form, $user);

        return $response;
    }
}
