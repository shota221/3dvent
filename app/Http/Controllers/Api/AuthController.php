<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Services as Service;

use Illuminate\Http\Request;

use App\Http\Forms as Form;

use App\Exceptions;

class AuthController extends ApiController
{
    private $service;

    function __construct()
    {
        $this->service = new Service\UserAuthService;
    }

    public function generateToken(Request $request)
    {
        $form = new Form\UserAuthForm($request->all());

        if ($form->hasError() || !$response = $this->service->generateToken($form)) {
            throw new Exceptions\InvalidFormException($form);
        }

        return $response;
    }

    public function removeToken(Request $request)
    {
        if(!$user = $request->hasHeader('X-User-Token') ? $this->getUser() : null){
            throw new Exceptions\InvalidException('auth.invalid_user_token');
        }

        return $this->service->removeToken($user);
    }
}
