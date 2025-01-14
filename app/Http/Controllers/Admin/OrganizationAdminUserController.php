<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions;
use App\Http\Controllers\Controller;
use App\Http\Forms\Admin as Form;
use App\Services\Admin as Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganizationAdminUserController extends Controller
{
    private $service;

    function __construct()
    {
        $this->service = new Service\OrganizationAdminUserService;
    }

    public function index(Request $request)
    {
        $path = $request->path();
        $organization_admin_users = $this->service->getPaginatedOrganizationAdminUserData($path);
        $organization_admin_users->withPath(route('admin.org_admin_user.search', [], false));
      
        return view('index', compact('organization_admin_users'));
    }

    public function asyncDataOrganization()
    {
        $response = $this->service->getOrganizationData();

        return $response;
    }

    
    public function asyncSearch(Request $request)
    {
        $form = new Form\OrganizationAdminUserSearchForm($request->all());

        if ($form->hasError()) throw new Exceptions\InvalidFormException($form);

        $path = $request->path();
        $organization_admin_users = $this->service->getPaginatedOrganizationAdminUserData($path, $form);

        return view('list', compact('organization_admin_users'));
    }

    public function asyncGetDetail(int $id)
    {
        $form = new Form\OrganizationAdminUserDetailForm(compact('id'));

        if ($form->hasError()) throw new Exceptions\InvalidFormException($form);
    
        return $this->service->getOneOrganizationAdminUserData($form);
    }

    public function asyncUpdate(Request $request)
    {
        $form = new Form\OrganizationAdminUserUpdateForm($request->all());

        if ($form->hasError()) throw new Exceptions\InvalidFormException($form);
    
        return $this->service->update($form, Auth::id());
    }

    public function asyncCreate(Request $request)
    {
        $form = new Form\OrganizationAdminUserCreateForm($request->all());

        if ($form->hasError()) throw new Exceptions\InvalidFormException($form);

        return $this->service->create($form, Auth::id());
    }
}
