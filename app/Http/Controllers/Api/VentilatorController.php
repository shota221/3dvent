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

    public function showValue(Request $request, $ventilator_id)
    {
        $request->merge(['ventilator_id' => $ventilator_id]);

        $form = new Form\VentilatorValueShowForm($request->all());

        if ($form->hasError() || !$response = $this->service->getVentilatorValueResult($form)) {
            throw new Exceptions\InvalidFormException($form);
        }

        return $response;
    }

    public function createValue(Request $request, $ventilator_id)
    {
        $request->merge(['ventilator_id' => $ventilator_id]);

        $form = new Form\VentilatorValueCreateForm($request->all());

        $user_token = $request->hasHeader('X-User-Token') ? $request->header('X-User-Token') : null;

        if (!$request->hasHeader('X-App-Key')) {
            $form->addError('X-App-Key', 'validation.appkey_required');
        } else {
            $appkey = $request->header('X-App-Key');
        }

        if ($form->hasError() || !$response = $this->service->createVentilatorValue($form, $user_token, $appkey)) {
            throw new Exceptions\InvalidFormException($form);
        }

        return $response;
    }

    public function updateValue(Request $request, $ventilator_id)
    {
        $request->merge(['ventilator_id' => $ventilator_id]);

        $form = new Form\VentilatorValueUpdateForm($request->all());

        if ($form->hasError()) {
            throw new Exceptions\InvalidFormException($form);
        }

        return $this->service->updateVentilatorValue($form);
    }

    //TODO　以下補完作業
    public function showValueList(Request $request)
    {
        return $this->service->getVentilatorValueListResult();
    }

    public function showDetailValue(Request $request)
    {
        return $this->service->getDetailVentilatorValueResult();
    }
    
    public function updateDetailValue(Request $request)
    {
        return $this->service->updateDetailVentilatorValue();
    }
}
