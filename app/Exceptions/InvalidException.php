<?php

namespace App\Exceptions;

use App\Http\Response\Message;
use App\Http\Response\Error;

/**
 * This is the invalid file exception class.
 */
class InvalidException extends \Exception
{
    public $errors = [];

    public function __construct(string $messageKey = null, array $messageReplacements = [], \Exception $previous = null)
    {
        $translated = '';

        if (! is_null($messageKey)) {
            $messageCode = trans_code($messageKey);

            $translated = trans($messageKey, $messageReplacements);

            $this->errors[] = new Error(null, new Message($messageCode, $translated));
        } else {
            // $messageKeyがNULLの場合はerrorsに入れずエクセプションのメッセージのみ
            $translated = trans('validation.invalid');
        }

        parent::__construct($translated, $previous);
    }
}