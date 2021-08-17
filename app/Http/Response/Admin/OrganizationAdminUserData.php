<?php

namespace App\Http\Response\Admin;

use App\Http\Response\SuccessJsonResult;

class OrganizationAdminUserData extends SuccessJsonResult 
{
    public $id;

    public $name;
   
    public $email;
    
    public $code;

    public $organization_name;

    public $created_at;

    public $status;

    public $disabled_flg;
}