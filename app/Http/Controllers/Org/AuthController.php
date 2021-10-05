<?php

namespace App\Http\Controllers\Org;

use App\Exceptions;
use App\Http\Controllers\Controller;
use App\Http\Forms as Form;
use App\Services as Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    private $service;
    private $user_auth_key;

    public function __construct()
    {
        $this->service = new Service\UserAuthService;
        $this->user_auth_key = 'org';
    }

    public function index()
    {
        if (Auth::guard($this->user_auth_key)->check()) {
            return redirect(route_path('org.home'));
        }
        
        return view('index');
    }

    public function login(Request $request)
    {
        if (Auth::guard($this->user_auth_key)->check()) {
            return redirect(route_path('org.home'));
        }

        $form = new Form\UserAuthForm($request->all());
 
        if ($form->hasError()) throw new Exceptions\InvalidFormException($form);

        $response = $this->service->login($form, Auth::guard($this->user_auth_key));

        return $response;
    }

    public function logout(Request $request)
    {   
        Auth::guard($this->user_auth_key)->logout();

        return redirect(route_path('org.auth'));
    }
}
