<?php

namespace App\Http\Controllers\Form;

use App\Exceptions\InvalidFormException;
use App\Http\Controllers\Controller;
use App\Http\Forms\Form as Form;
use App\Services\Form as Service;
use Illuminate\Http\Request;

class OrganizationRegistrationController extends Controller
{
    private $service;

    function __construct()
    {
        $this->service = new Service\OrganizationRegistrationService;
    }

    function index()
    {
        return view('index');
    }

    function create(Request $request)
    {
        $form = new Form\OrganizationRegistrationForm($request->all());
        
        if ($form->hasError()) throw new InvalidFormException($form);

        return $this->service->create($form);
    }
}
