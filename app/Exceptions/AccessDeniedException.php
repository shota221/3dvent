<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;

class AccessDeniedException extends AuthorizationException
{
    public function __construct($message = null, $code = null, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}