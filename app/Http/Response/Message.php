<?php

namespace App\Http\Response;

class Message {

    public $code = 0;

    public $translated = '';
    
    public function __construct(int $messageCode = null, string $translated = null) 
    {
        $this->code = $messageCode;

        $this->translated = $translated;
    }
}