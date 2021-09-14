<?php

namespace App\Http\Controllers\Org;

use App\Exceptions;
use App\Http\Controllers\Controller;
use App\Http\Forms\Org as Form;
use App\Services\Org as Service;
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
        $form = new Form\VentilatorValueSearchForm($request->all());

        if ($form->hasError()) throw new Exceptions\InvalidFormException($form);

        $path = $request->path();
        $ventilator_values = $this->service->getPaginatedVentilatorValueData($path,$form);
        $ventilator_values->withPath(route('org.ventilator_value.search', $request->input(), false));
      
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

    public function asyncGetDetail(Request $request)
    {
        $form = new Form\VentilatorValueDetailForm($request->all());

        if ($form->hasError()) throw new Exceptions\InvalidFormException($form);

        return $this->service->getOneVentilatorValueData($form);
    }

    function asyncUpdate(Request $request)
    {
        $form = new Form\VentilatorValueUpdateForm($request->all());

        if ($form->hasError()) throw new Exceptions\InvalidFormException($form);

        return $this->service->update($form);
    }

    public function asyncBulkDelete(Request $request)
    {
        $form = new Form\VentilatorValueBulkDeleteForm($request->all());

        if ($form->hasError()) throw new Exceptions\InvalidFormException($form);

        return $this->service->bulkDelete($form);
    }
}