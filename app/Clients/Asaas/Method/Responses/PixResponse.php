<?php

namespace App\Clients\Asaas\Method\Responses;

class PixResponse extends AbstractMethodResponse
{
    public function getResult(): string
    {
        return "{$this->response['encodedImage']}@{$this->response['payload']}";
    }
}
