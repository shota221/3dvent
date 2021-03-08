<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Services\Api as Service;

use Illuminate\Http\Request;

use App\Http\Forms\Api as Form;

use App\Exceptions;

class AppkeyController extends Controller
{
    private $service;
    
    function __construct() 
    {
        $this->service = new Service\AppkeyService;
    }

    public function create(Request $request)
    {
        $form = new Form\AppkeyCreateForm($request->all());

        if ($form->hasError() || !$response = $this->service->create($form)) {
            throw new Exceptions\InvalidFormException($form);
        }

        return $response;
    }
}
