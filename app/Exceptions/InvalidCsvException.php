<?php

namespace App\Exceptions;

/**
 * This is the invalid file exception class.
 */
class InvalidCsvException extends InvalidException
{
    public $finishedRowCount;
    public $finishedRowCountMessage;

    public function __construct(string $messageKey = null, array $messageReplacements = [], int $finishedRowCount = 0,  \Exception $previous = null)
    {
        $this->finishedRowCount        = $finishedRowCount;
        $this->finishedRowCountMessage = ' ヘッダ行含む処理済み行数=' . $finishedRowCount;

        parent::__construct($messageKey, $messageReplacements, $previous);
    }
}