<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function create(Request $request)
    {
        var_dump($request->all());
        return 'test';
    }

    public function read(Request $request,$id)
    {
        var_dump($request->all());
        return 'test';
    }

    public function update(Request $request,$id)
    {
        var_dump($request->all());
        return 'test';
    }
}
