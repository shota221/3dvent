<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions;
use App\Http\Controllers\Controller;
use App\Http\Forms\Admin as Form;
use App\Services\Admin as Service;
use Illuminate\Http\Request;

class VentilatorValueController extends Controller
{
    private $service;

    function __construct()
    {
        $this->service = new Service\VentilatorValueService;
    }

    public function index(Request $request)
    {
        $path = $request->path();
        $ventilator_values = $this->service->getPaginatedVentilatorValueData($path);
        $ventilator_values->withPath(route('admin.ventilator_value.search', [], false));
      
        return view('index', compact('ventilator_values'));
    }

    public function asyncSearch(Request $request)
    {
        $form = new Form\VentilatorValueSearchForm($request->all());

        if ($form->hasError()) throw new Exceptions\InvalidFormException($form);
        
        $path = $request->path();
        $ventilator_values = $this->service->getPaginatedVentilatorValueData($path, $form);

        return view('list', compact('ventilator_values'));
    }

    // public function asyncEdit(Request $request)
    // {
    //     $form = new Form\VentilatorValueEditForm($request->all());

    //     if ($form->hasError()) throw new Exceptions\InvalidFormException($form);

    //     return $this->service->getOneVentilatorValueData($form);
    // }

    // function asyncUpdate(Request $request)
    // {
    //     $form = new Form\VentilatorUpdateForm($request->all());

    //     if ($form->hasError()) throw new InvalidFormException($form);

    //     return $this->service->update($form);
    // }

}