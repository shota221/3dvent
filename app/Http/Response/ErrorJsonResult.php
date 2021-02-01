<?php 

namespace App\Http\Response;

/**
 * Api error Json response
 */
class ErrorJsonResult extends JsonResult {

    public $errors;

    /**
     * [__construct description]
     * @param array App\Http\Response\Error $errors [description]
     */
    public function __construct(array $errors)
    {
        $this->errors = $errors;
    }
}