<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index(Request $request)
    {
        var_dump($request->all());
        return 'test';
    }
}
