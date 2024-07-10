<?php

namespace App\Clients\Asaas\Method\Responses;

class TicketResponse extends AbstractMethodResponse
{
    public function getResult(): string
    {
        return $this->response['bankSlipUrl'];
    }
}
