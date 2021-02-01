<?php

namespace App\Exceptions;

use Exception;

class CsrfTokenMismatchException extends Exception
{
    //
    public function __construct()
    {
        parent::__construct(trans('validation.token_missmatched'));
    }
}
