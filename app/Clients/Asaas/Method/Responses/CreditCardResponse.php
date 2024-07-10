<?php

namespace App\Clients\Asaas\Method\Responses;

class CreditCardResponse extends AbstractMethodResponse
{
    public function getResult(): string
    {
        return $this->response['status'];
    }
}
