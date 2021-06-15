<?php


namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 */
class HttpNotFoundException extends NotFoundHttpException
{
    /**
     * @param string     $message  The internal exception message
     * @param \Exception $previous The previous exception
     */
    public function __construct(string $message = null, \Exception $previous = null)
    {
        parent::__construct($message, $previous);
    }
}