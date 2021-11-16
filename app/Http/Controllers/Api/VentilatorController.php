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

        //組織紐付けされている呼吸器へのログインユーザ組織整合性チェック用
        $user = $this->getUser();

        if ($form->hasError()) {
            throw new Exceptions\InvalidFormException($form);
        }

        $response = $this->service->getVentilatorResult($form, $user);

        return $response;
    }

    public function create(Request $request)
    {
        $form = new Form\VentilatorCreateForm($request->all());

        $user = $this->getUser();

        if ($form->hasError()) {
            throw new Exceptions\InvalidFormException($form);
        }

        $response = $this->service->create($form, $user);

        return $response;
    }

    public function update(Request $request, int $id)
    {
        $request->merge(['id' => $id]);
        
        $form = new Form\VentilatorUpdateForm($request->all());

        $user = $this->getUser();

        if ($form->hasError()) {
            throw new Exceptions\InvalidFormException($form);
        }

        $response = $this->service->update($form, $user);

        return $response;
    }

    public function deactivate(Request $request, int $id)
    {
        $form = new Form\VentilatorDeactivateForm(compact('id'));

        $user = $this->getUser();

        if ($form->hasError()) {
            throw new Exceptions\InvalidFormException($form);
        }

        $response = $this->service->deactivate($form ,$user);

        return $response;
    }
}
