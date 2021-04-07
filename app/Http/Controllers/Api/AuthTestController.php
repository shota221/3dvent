<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Services\Api as Service;

use Illuminate\Http\Request;

use App\Exceptions;

class AuthTestController extends ApiController
{
    

    public function index(Request $request)
    {
        var_dump($this->getUser()->name);
    }
}
