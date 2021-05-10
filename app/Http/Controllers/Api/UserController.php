<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Services\Api as Service;

use Illuminate\Http\Request;

use App\Http\Forms\Api as Form;

use App\Exceptions;
use App\Exceptions\InvalidFormException;

class UserController extends ApiController
{
    private $service;

    function __construct()
    {
        $this->service = new Service\UserService;
    }

    public function show(Request $request)
    {
        $user = $this->getUser();

        return  $this->service->getUserResult($user);
    }

    public function update(Request $request)
    {
        $form = new Form\UserUpdateForm($request->all());

        $user = $this->getUser();

        if ($form->hasError() || !$response = $this->service->update($form, $user)) {
            throw new Exceptions\InvalidFormException($form);
        }

        return $response;
    }
}
