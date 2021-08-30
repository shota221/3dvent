<?php

namespace App\Exceptions;

/**
 * This is the invalid file exception class.
 */
class InvalidCsvException extends InvalidException
{
    public function __construct(string $messageKey = null, array $messageReplacements = [], \Exception $previous = null)
    {
        parent::__construct($messageKey, $messageReplacements ,$previous);
    }
}