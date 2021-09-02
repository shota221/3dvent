<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions;
use App\Http\Controllers\Controller;
use App\Http\Forms\Admin as Form;
use App\Services\Admin as Service;
use Illuminate\Http\Request;

class PatientValueController extends Controller
{
    private $service;

    function __construct()
    {
        $this->service = new Service\PatientValueService;
    }

    public function index(Request $request)
    {
        $path = $request->path();
        $patient_values = $this->service->getPaginatedPatientValueData($path);
        $patient_values->withPath(route('admin.patient_value.search', [], false));
      
        return view('index', compact('patient_values'));
    }

    public function asyncSearch(Request $request)
    {
        $form = new Form\PatientValueSearchForm($request->all());

        if ($form->hasError()) throw new Exceptions\InvalidFormException($form);
        
        $path = $request->path();
        $patient_values = $this->service->getPaginatedPatientValueData($path, $form);

        return view('list', compact('patient_values'));
    }

    public function asyncEdit(Request $request)
    {
        $form = new Form\PatientValueEditForm($request->all());

        if ($form->hasError()) throw new Exceptions\InvalidFormException($form);

        return $this->service->getOnePatientValueData($form);
    }

    public function asyncUpdate(Request $request)
    {
        $form = new Form\PatientValueUpdateForm($request->all());

        if ($form->hasError()) throw new Exceptions\InvalidFormException($form);
        
        return $this->service->update($form);
    }
    
    public function asyncDataOrganization()
    {
        $response = $this->service->getOrganizationData();

        return $response;
    }
}