<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\InvalidFormException;
use App\Http\Controllers\Controller;
use App\Http\Forms\Admin as Form;
use App\Services\Admin as Service;
use App\Services\Support\Gs1Util;
use Illuminate\Http\Request;

class VentilatorController extends Controller
{
    private $service;

    function __construct()
    {
        $this->service = new Service\VentilatorService;
    }

    function index(Request $request)
    {
        $base_url = $request->url();
        $ventilator_paginator = $this->service->getVentilatorData($base_url);
        $ventilator_paginator->withPath($base_url.'/async');
        // return view('index', compact('ventilator_paginator'));
        return var_dump($ventilator_paginator);
    }

    function asyncSearch(Request $request)
    {
        $form = new Form\VentilatorSearchForm($request->all());
        $base_url = $request->url();
        $ventilator_paginator = $this->service->getVentilatorData($base_url, $form);
        return view('list', compact('ventilator_paginator'));
    }

    function asyncCreate(Request $request)
    {
        // $form = new Form\VentilatorForm($request->all());

        // if ($form->hasError()) throw new InvalidFormException($form);

        // return $this->service->create($form);
    }

    function asyncUpdate(Request $request)
    {
        // $form = new Form\VentilatorUpdateForm($request->all());

        // if ($form->hasError()) throw new InvalidFormException($form);

        // return $this->service->update($form);
    }
}
