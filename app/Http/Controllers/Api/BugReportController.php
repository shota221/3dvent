<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Services\Api as Service;

use Illuminate\Http\Request;

use App\Http\Forms\Api as Form;

use App\Exceptions;

class BugReportController extends ApiController
{
    private $service;

    function __construct()
    {
        $this->service = new Service\BugReportService;
    }

    public function create(Request $request)
    {
        $form = new Form\BugReportCreateForm($request->all());

        if ($form->hasError()) throw new Exceptions\InvalidFormException($form);

        $user = $this->getUser();

        $appkey = $this->getAppkey();

        $response = $this->service->create($form, $appkey, $user);

        return $response;
    }
}