<?php

namespace App\Http\Controllers\Admin;

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

    function show()
    {
        return view('index');
    }
}
