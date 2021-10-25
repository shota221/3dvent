<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Services as Service;

use Illuminate\Support\Facades\Auth;

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

        if ($form->hasError()) throw new Exceptions\InvalidFormException($form);
        
        $response = $this->service->generateToken($form, Auth::guard('user'));

        return $response;
    }

    public function removeToken()
    {
        $user = $this->getUser();

        return $this->service->removeToken($user);
    }

    /**
     * ユーザートークンを有しているか(ログイン中)どうかを判定
     *
     * @param Request $request
     * @return void
     */
    public function check(Request $request)
    {
        $form = new Form\HasTokenCheckForm($request->all());

        if ($form->hasError() || !$response = $this->service->checkHasToken($form)) {
            throw new Exceptions\InvalidFormException($form);
        }

        return $response;
    }
}
