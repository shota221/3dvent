<?php

namespace App\Http\Controllers\Form;

use App\Exceptions\InvalidFormException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Form as Service;
use App\Http\Forms\Form as Form;

class OrgRegistrationApplicationController extends Controller
{
    private $service;

    function __construct()
    {
        $this->service = new Service\OrgRegistrationApplicationService;
    }

    function show()
    {
        return view('index');
    }

    function create(Request $request)
    {
        $form = new Form\OrgRegistrationApplicationForm($request->all());
        if($form->hasError()){
            throw new InvalidFormException($form);
        }
            return $this->service->create($form);
    }
}
