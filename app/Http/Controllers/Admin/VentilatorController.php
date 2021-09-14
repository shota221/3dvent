<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\InvalidFormException;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Support\CsvLoader;
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

    public function index(Request $request)
    {
        $path = $request->path();
        $ventilator_paginator = $this->service->getVentilatorData($path);
        $ventilator_paginator->withPath(route('admin.ventilator.async'), [], false);
        return view('index', compact('ventilator_paginator'));
    }

    public function asyncSearch(Request $request)
    {
        $form = new Form\VentilatorSearchForm($request->all());

        if ($form->hasError()) throw new InvalidFormException($form);

        $path = $request->path();
        $ventilator_paginator = $this->service->getVentilatorData($path, $form);
        return view('list', compact('ventilator_paginator'));
    }

    public function asyncUpdate(Request $request)
    {
        $form = new Form\VentilatorUpdateForm($request->all());

        if ($form->hasError()) throw new InvalidFormException($form);

        return $this->service->update($form);
    }

    public function asyncGetPatientData(Request $request)
    {
        $form = new Form\VentilatorPatientForm($request->all());

        if ($form->hasError()) throw new InvalidFormException($form);

        return $this->service->getPatient($form);
    }

    public function asyncBulkDelete(Request $request)
    {
        $form = new Form\VentilatorBulkDeleteForm($request->all());

        if ($form->hasError()) throw new InvalidFormException($form);

        return $this->service->bulkDelete($form);;
    }

    public function asyncShowBugList(Request $request)
    {
        $form = new Form\VentilatorBugsForm($request->all());

        if ($form->hasError()) throw new InvalidFormException($form);

        $bugs = $this->service->getBugList($form);

        return view('ventilatorBugList', compact('bugs'));
    }

    public function exportCsv(Request $request)
    {
        $form = new Form\VentilatorCsvExportForm($request->all());

        if ($form->hasError()) throw new InvalidFormException($form);

        return response()->streamDownload(
            function () use ($form) {
                $this->service->createVentilatorCsv($form);
            },
            config('ventilator_csv.filename')
        );
    }

    public function importCsv(Request $request)
    {
        $form = new Form\VentilatorCsvImportForm($request->all());

        if ($form->hasError()) throw new InvalidFormException($form);

        $file = $request->file('csv_file');

        $response = $this->service->create($form, $file);

        return $response;
    }
}
