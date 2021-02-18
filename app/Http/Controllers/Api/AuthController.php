<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Services as Service;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    private $service;
    
    function __construct() 
    {
        $this->service = new Service\UserAuthService;
    }
    
    public function login(Request $request)
    {
        return $this->service->login();
    }

    public function logout(Request $request)
    {
        return $this->service->logout();
    }
}