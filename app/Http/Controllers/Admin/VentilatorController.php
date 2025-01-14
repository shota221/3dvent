<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Support\CsvLoader;
use App\Http\Forms\Admin as Form;
use App\Services\Admin as Service;
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
        $ventilator_paginator = $this->service->getVentilatorData($path);
        $ventilator_paginator->withPath(route('admin.ventilator.async'), [], false);
        return view('index', compact('ventilator_paginator'));
    }

    public function asyncSearch(Request $request)
    {
        $form = new Form\VentilatorSearchForm($request->all());

        if ($form->hasError()) throw new Exceptions\InvalidFormException($form);

        $path = $request->path();
        $ventilator_paginator = $this->service->getVentilatorData($path, $form);
        return view('list', compact('ventilator_paginator'));
    }

    public function asyncUpdate(Request $request)
    {
        $form = new Form\VentilatorUpdateForm($request->all());

        if ($form->hasError()) throw new Exceptions\InvalidFormException($form);

        return $this->service->update($form);
    }

    public function asyncGetPatientData(Request $request)
    {
        $form = new Form\VentilatorPatientForm($request->all());

        if ($form->hasError()) throw new Exceptions\InvalidFormException($form);

        return $this->service->getPatient($form);
    }

    public function asyncBulkDelete(Request $request)
    {
        $form = new Form\VentilatorBulkDeleteForm($request->all());

        if ($form->hasError()) throw new Exceptions\InvalidFormException($form);

        return $this->service->bulkDelete($form);;
    }

    public function asyncShowBugList(Request $request)
    {
        $form = new Form\VentilatorBugsForm($request->all());

        if ($form->hasError()) throw new Exceptions\InvalidFormException($form);

        $bugs = $this->service->getBugList($form);

        return view('ventilatorBugList', compact('bugs'));
    }

    /**
     * ジョブにキューを登録
     *
     * @param Request $request
     */
    public function asyncQueueOutputVentilatorData(Request $request)
    {
        $form = new Form\VentilatorCsvExportForm($request->all());

        if ($form->hasError()) throw new Exceptions\InvalidFormException($form);

        $response = $this->service->startQueueVentilatorDataCsvJob($form);
        
        return $response;
    }

    /**
     * キューの状況を確認
     *
     * @param Request $request
     */
    public function asyncQueueStatusOutputVentilatorData(Request $request)
    {
        $form = new Form\QueueStatusCheckForm($request->all());

        if ($form->hasError()) throw new Exceptions\InvalidFormException($form);

        $response = $this->service->checkStatusVentilatorDataCsvJob($form);

        return $response;
    }

    /**
     * キューが完了していればCSV出力
     *
     * @param Request $request
     */
    public function exportCsv(Request $request)
    {
        $form = new Form\QueueStatusCheckForm($request->all());

        if ($form->hasError()) throw new Exceptions\InvalidFormException($form);

        $file_path = $this->service->getCreatedVentilatorDataCsvFilePath($form);

        return response()->download($file_path)->deleteFileAfterSend(true);
    }

    /**
     * キューに詰める
     */
    public function asyncQueueInputVentilatorData(Request $request)
    {
        $form = new Form\VentilatorCsvImportForm($request->all());

        if ($form->hasError()) throw new Exceptions\InvalidFormException($form);

        $response = $this->service->startQueueVentilatorDataImportJob($form, Auth::user());

        return $response;
    }

    /**
     * キューの状況確認
     */
    public function asyncQueueStatusInputVentilatorData(Request $request)
    {
        $form = new Form\QueueStatusCheckForm($request->all());

        if ($form->hasError()) throw new Exceptions\InvalidFormException($form);

        $response = $this->service->checkStatusVentilatorDataImportJob($form);

        return $response;
    }
}
