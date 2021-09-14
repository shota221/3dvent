<?php

namespace App\Http\Controllers\Org;

use App\Exceptions;
use App\Http\Controllers\Controller;
use App\Http\Forms\Org as Form;
use App\Services\Org as Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class OrganizationSettingController extends Controller
{
    private $service;

    function __construct()
    {
        $this->service = new Service\OrganizationSettingService;
    }

    public function index() 
    {
        $setting = $this->service->getOrganizationSettingData(Auth::user()->organization_id);
        
        return view('index', compact('setting'));
    }

    public function asyncUpdate(Request $request) 
    {
        $form = new Form\OrganizationSettingUpdateForm($request->all());

        if ($form->hasError()) throw new Exceptions\InvalidFormException($form);

        $response = $this->service->update($form, Auth::user()->organization_id, Auth::id());

        return $response;
    }
}
