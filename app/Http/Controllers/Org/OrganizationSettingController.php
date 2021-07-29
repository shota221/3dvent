<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exceptions;
use App\Services\Org as Service;
use App\Http\Forms\Org as Form;


class OrganizationSettingController extends Controller
{
    private $service;

    function __construct()
    {
        $this->service = new Service\OrganizationSettingService;
    }

    public function index() 
    {
        $setting = $this->service->getOrganizationSettingData();
        
        return view('index', compact('setting'));
    }

    public function asyncUpdate(Request $request) 
    {
        $form = new Form\OrganizationSettingUpdateForm($request->all());

        if ($form->hasError()) throw new Exceptions\InvalidFormException($form);

        $response = $this->service->update($form);

        return $response;
    }
}
