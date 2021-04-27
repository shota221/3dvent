<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Services\Api as Service;

use Illuminate\Http\Request;

use App\Http\Forms\Api as Form;

use App\Exceptions;

class VentilatorController extends ApiController
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

        $user = $this->getUser();

        if ($form->hasError() || !$response = $this->service->create($form, $user)) {
            throw new Exceptions\InvalidFormException($form);
        }

        return $response;
    }

    public function update(Request $request, $id)
    {
        $request->merge(['id' => $id]);

        $form = new Form\VentilatorUpdateForm($request->all());

        if ($form->hasError() || !$response = $this->service->update($form)) {
            throw new Exceptions\InvalidFormException($form);
        }

        return $response;
    }
}
