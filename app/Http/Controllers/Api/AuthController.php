<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        var_dump($request->all());
        return 'test';
    }

    public function logout(Request $request)
    {
        var_dump($request->all());
        return 'test';
    }
}