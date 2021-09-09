<?php

namespace App\Http\Controllers\Org;

use App\Exceptions;
use App\Http\Controllers\Controller;
use App\Http\Forms\Org as Form;
use App\Services\Org as Service;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $service;

    function __construct()
    {
        $this->service = new Service\UserService;
    }

    public function index(Request $request)
    {
        $path  = $request->path();
        $users = $this->service->getPaginatedUserData($path);
        $users->withPath(route('org.user.search', [], false));

        return view('index', compact('users'));
    }

    public function asyncSearch(Request $request)
    {
        $form = new Form\UserSearchForm($request->all());

        if ($form->hasError()) throw new Exceptions\InvalidFormException($form);

        $path  = $request->path();
        $users = $this->service->getPaginatedUserData($path, $form);

        return view('list', compact('users'));
    } 
    
    public function asyncDetail(Request $request)
    {   
        $form = new Form\UserDetailForm($request->all());

        if ($form->hasError()) throw new Exceptions\InvalidFormException($form);

        return $this->service->getOneUserData($form);
    }
    
    public function asyncUpdate(Request $request)
    {
        $form = new Form\UserUpdateForm($request->all());

        if ($form->hasError()) throw new Exceptions\InvalidFormException($form);

        return $this->service->update($form);
    }
    
    public function asyncCreate(Request $request)
    {
        $form = new Form\UserCreateForm($request->all());

        if ($form->hasError()) throw new Exceptions\InvalidFormException($form);

        return $this->service->create($form);
    }
    
    public function asyncLogicalDelete(Request $request)
    {
        $form = new Form\UserLogicalDeleteForm($request->all());

        if ($form->hasError()) throw new Exceptions\InvalidFormException($form);

        return $this->service->logicalDelete($form);
    }

    public function asyncExportCsvUserFormat()
    {

    }

    public function asyncImportCsvUserData()
    {
        
    }
}
