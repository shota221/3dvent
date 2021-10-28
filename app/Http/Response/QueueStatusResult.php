<?php 
 
namespace App\Http\Response;

use App\Http\Response\SuccessJsonResult;

class QueueStatusResult extends SuccessJsonResult
{
    public $queue;
    
    public $is_finished = false;

    public $has_error = false;
}