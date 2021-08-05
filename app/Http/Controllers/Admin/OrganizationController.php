<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\InvalidFormException;
use App\Http\Controllers\Controller;
use App\Services\Admin as Service;
use App\Http\Forms\Admin as Form;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    private $service;

    function __construct()
    {
        $this->service = new Service\OrganizationService;
    }

    function index(Request $request)
    {
        $base_url = $request->url();
        $organization_paginator = $this->service->getOrganizationData($base_url);
        $organization_paginator->withPath($base_url.'/async');
        return view('index', compact('organization_paginator'));
    }

    function asyncSearch(Request $request)
    {
        $form = new Form\OrganizationSearchForm($request->all());
        $base_url = $request->url();
        $organization_paginator = $this->service->getOrganizationData($base_url, $form);
        return view('list', compact('organization_paginator'));
    }

    function asyncCreate(Request $request)
    {
        $form = new Form\OrganizationForm($request->all());

        if ($form->hasError()) throw new InvalidFormException($form);

        return $this->service->create($form);
    }

    function asyncUpdate(Request $request)
    {
        $form = new Form\OrganizationUpdateForm($request->all());

        if ($form->hasError()) throw new InvalidFormException($form);

        return $this->service->update($form);
    }
}
