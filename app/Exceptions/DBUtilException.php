<?php

namespace App\Exceptions;

/**
 * This is the invalid file exception class.
 */
class DBUtilException extends \Exception
{
    /**
     * @param string     $message  The internal exception message
     * @param \Exception $previous The previous exception
     */
    public function __construct(string $message = null, \Exception $previous = null)
    {
        parent::__construct($message, null, $previous);
    }
}