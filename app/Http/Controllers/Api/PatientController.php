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

        if ($form->hasError() || !$response = $this->service->create($form, $user)) {
            throw new Exceptions\InvalidFormException($form);
        }

        return $response;
    }

    public function show(Request $request, $id)
    {
        $request->merge(['id' => $id]);

        $form = new Form\PatientShowForm($request->all());

        if ($form->hasError() || !$response = $this->service->getPatientResult($form)) {
            throw new Exceptions\InvalidFormException($form);
        }

        return $response;
    }

    public function update(Request $request, $id)
    {
        $request->merge(['id' => $id]);

        $form = new Form\PatientUpdateForm($request->all());

        if ($form->hasError() || !$response = $this->service->update($form)) {
            throw new Exceptions\InvalidFormException($form);
        }

        return $response;
    }

    public function showDetail(Request $request, $id)
    {
        $request->merge(['id' => $id]);

        $form = new Form\PatientShowForm($request->all());

        if ($form->hasError() || !$response = $this->service->getPatientValueResult($form)) {
            throw new Exceptions\InvalidFormException($form);
        }

        return $response;
    }

    public function createDetail(Request $request, $id)
    {
        $request->merge(['id' => $id]);

        $form = new Form\PatientValueForm($request->all());

        $user = $this->getUser();

        if ($form->hasError() || !$response = $this->service->createPatientValue($form, $user)) {
            throw new Exceptions\InvalidFormException($form);
        }

        return $response;
    }

    public function updateDetail(Request $request, $id)
    {
        $request->merge(['id' => $id]);

        $form = new Form\PatientValueForm($request->all());

        $user = $this->getUser();

        if ($form->hasError() || !$response = $this->service->updatePatientValue($form, $user)) {
            throw new Exceptions\InvalidFormException($form);
        }

        return $response;
    }
}
