<?php

namespace App\Clients\Asaas\Method\Responses;

class CrediCardResponse extends AbstractMethodResponse
{
    public function getResult(): string
    {
        return $this->response['status'];
    }
}
