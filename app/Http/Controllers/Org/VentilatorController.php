<?php

namespace App\Http\Controllers\Org;

use App\Exceptions;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Support\CsvLoader;
use App\Http\Forms\Org as Form;
use App\Services\Org as Service;
use App\Services\Support\Gs1Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VentilatorController extends Controller
{
    private $service;

    function __construct()
    {
        $this->service = new Service\VentilatorService;
    }

    public function index(Request $request)
    {
        $path = $request->path();
        $ventilator_paginator = $this->service->getVentilatorData($path, Auth::user());
        $ventilator_paginator->withPath(route('org.ventilator.search'), [], false);
        return view('index', compact('ventilator_paginator'));
    }

    public function asyncSearch(Request $request)
    {
        $form = new Form\VentilatorSearchForm($request->all());

        if ($form->hasError()) throw new Exceptions\InvalidFormException($form);

        $path = $request->path();
        $ventilator_paginator = $this->service->getVentilatorData($path, Auth::user(), $form);
        return view('list', compact('ventilator_paginator'));
    }

    public function asyncUpdate(Request $request)
    {
        $form = new Form\VentilatorUpdateForm($request->all());

        if ($form->hasError()) throw new Exceptions\InvalidFormException($form);

        return $this->service->update($form, Auth::user());
    }

    public function asyncGetPatientData(Request $request)
    {
        $form = new Form\VentilatorPatientForm($request->all());

        if ($form->hasError()) throw new Exceptions\InvalidFormException($form);

        return $this->service->getPatient($form, Auth::user());
    }

    public function asyncShowBugList(Request $request)
    {
        $form = new Form\VentilatorBugsForm($request->all());

        if ($form->hasError()) throw new Exceptions\InvalidFormException($form);

        $bugs = $this->service->getBugList($form, Auth::user());

        return view('ventilatorBugList', compact('bugs'));
    }
}
