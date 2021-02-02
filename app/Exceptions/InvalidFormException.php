<?php

namespace App\Exceptions;

/**
 * This is the invalid file exception class.
 */
class InvalidFormException extends InvalidException
{
    public function __construct(\App\Http\Forms\BaseForm $form)
    {
        $this->errors = $form->errors;
        
        parent::__construct();
    }
}