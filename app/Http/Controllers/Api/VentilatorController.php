<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class VentilatorController extends Controller
{
    public function read(Request $request)
    {
        var_dump($request->all());
        return 'test';
    }

    public function create(Request $request)
    {
        var_dump($request->all());
        return 'test';
    }

    public function readValues(Request $request,$id)
    {
        var_dump($request->all());
        return 'test';
    }

    public function updateValues(Request $request,$id)
    {
        var_dump($request->all());
        return 'test';
    }
}
