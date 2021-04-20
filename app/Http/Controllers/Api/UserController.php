<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Services\Api as Service;

use Illuminate\Http\Request;

use App\Http\Forms\Api as Form;

use App\Exceptions;

class UserController extends ApiController
{
    private $service;

    function __construct()
    {
        $this->service = new Service\UserService;
    }

    public function show()
    {
        if (!$user = $this->getUser()) {
            throw new Exceptions\InvalidException('auth.invalid_user_token');
        }

        return $this->service->getUserResult($user);
    }

    public function update(Request $request)
    {
        $form = new Form\UserUpdateForm($request->all());

        if (!$user = $this->getUser()) {
            throw new Exceptions\InvalidException('auth.invalid_user_token');
        }

        if ($form->hasError() || !$response = $this->service->update($form, $user)) {
            throw new Exceptions\InvalidFormException($form);
        }

        return $response;
    }
}
