<?php

namespace App\Services\Support\Client\Response;

class CreateRoomResponse
{
    public $token;

    public function __construct($data)
    {
        $this->token = $data['token'];
    }
}
