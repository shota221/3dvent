<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\InvalidFormException;
use App\Http\Controllers\Controller;
use App\Http\Forms\Admin as Form;
use App\Services\Admin as Service;
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
        $path = $request->path();
        $organization_paginator = $this->service->getOrganizationData($path);
        $organization_paginator->withPath(route('admin.organization.async',[],false));
        
        return view('index', compact('organization_paginator'));
    }

    function asyncSearch(Request $request)
    {
        $form = new Form\OrganizationSearchForm($request->all());

        if ($form->hasError()) throw new InvalidFormException($form);

        $path = $request->path();
        $organization_paginator = $this->service->getOrganizationData($path, $form);

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

    function asyncUsers(Request $request)
    {
        $form = new Form\OrganizationUsersForm($request->all());

        $users = $this->service->getUsersList($form);

        return view('userList', compact('users'));
    }
}
