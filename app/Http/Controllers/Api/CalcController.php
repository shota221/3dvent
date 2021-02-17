<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class CalcController extends Controller
{
    public function defaultFlow(Request $request)
    {
        var_dump($request->all());
        return 'test';
    }

    public function estimatedData(Request $request)
    {
        var_dump($request->all());
        return 'test';
    }

    public function ieManual(Request $request)
    {
        var_dump($request->all());
        return 'test';
    }

    public function ieSound(Request $request)
    {
        var_dump($request->all());
        return 'test';
    }
}
