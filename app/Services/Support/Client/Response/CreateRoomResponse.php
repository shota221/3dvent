<?php

namespace App\Services\Support\Client\Response;

class CreateRoomResponse
{
    public $token;

    public $name;

    public function __construct($data)
    {
        $this->token = $data['token'];

        $this->name = $data['name'];
    }
}
