<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Services\Api as Service;

use Illuminate\Http\Request;

use App\Http\Forms\Api as Form;

use App\Exceptions;

class VentilatorValueController extends ApiController
{
    private $service;

    function __construct()
    {
        $this->service = new Service\VentilatorValueService;
    }

    public function show(Request $request, $id)
    {
        $request->merge(['id' => $id]);

        $form = new Form\VentilatorValueShowForm($request->all());

        if ($form->hasError() || !$response = $this->service->getVentilatorValueResult($form)) {
            throw new Exceptions\InvalidFormException($form);
        }

        return $response;
    }

    public function create(Request $request)
    {
        $form = new Form\VentilatorValueCreateForm($request->all());

        $user = $this->getUser();

        $appkey = $this->getAppkey();

        if ($form->hasError() || !$response = $this->service->create($form, $user, $appkey)) {
            throw new Exceptions\InvalidFormException($form);
        }

        return $response;
    }

    public function update(Request $request, $id)
    {
        $request->merge(['id' => $id]);

        $form = new Form\VentilatorValueUpdateForm($request->all());

        $user = $this->getUser();

        if ($form->hasError() || !$response = $this->service->update($form, $user)) {
            throw new Exceptions\InvalidFormException($form);
        }

        return $response;
    }

    public function list(Request $request)
    {
        $form = new Form\VentilatorValueListForm($request->all());

        if($form->hasError() || !$response = $this->service->getVentilatorValueListResult($form)){
            throw new Exceptions\InvalidFormException($form);
        }

        return $response;
    }
}
