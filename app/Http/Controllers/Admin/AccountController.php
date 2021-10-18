<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions;
use App\Http\Controllers\Controller;
use App\Http\Forms\Admin as Form;
use App\Services\Admin\UserAccountService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    private $service;

    function __construct()
    {
        $this->service = new UserAccountService;
    }
    
    public function asyncDataProfile()
    {
        $response = $this->service->getProfileData(Auth::guard()->user());

        return $response;
    }

    public function asyncUpdateProfile(Request $request)
    {
        $form = new Form\AdminUserProfileForm($request->all());

        if ($form->hasError()) throw new Exceptions\InvalidFormException($form);

        $response = $this->service->updateProfile($form, Auth::guard()->user());

        return $response;
    }

    
}