<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions;
use App\Http\Controllers\Controller;
use App\Http\Forms as Form;
use App\Services as Service;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private $service;
    private $user_auth_key;

    public function __construct()
    {
        $this->service = new Service\UserAuthService;
        $this->user_auth_key = 'admin';
    }

    public function index()
    {
        if ($this->service->loggedin($this->user_auth_key)) {
            return redirect(route_path('admin.home'));
        }
        
        return view('index');
    }

    public function login(Request $request)
    {
        if ($this->service->loggedin($this->user_auth_key)) {
            return redirect(route_path('admin.home'));
        }

        $form = new Form\UserAuthForm($request->all());
 
        if ($form->hasError()) throw new Exceptions\InvalidFormException($form);

        $response = $this->service->login($form, $this->user_auth_key);

        return $response;
    }

    public function logout(Request $request)
    {   
        $this->service->logout($this->user_auth_key);

        return redirect(route_path('admin.auth'));
    }
}
