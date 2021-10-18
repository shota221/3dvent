<?php 
 
namespace App\Http\Response;

use App\Http\Response\SuccessJsonResult;

class UserProfileJsonResult extends SuccessJsonResult {

    public $name;
    public $email;
}