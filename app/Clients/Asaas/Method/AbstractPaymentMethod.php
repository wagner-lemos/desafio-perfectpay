<?php

declare(strict_types=1);

namespace App\Clients\Asaas\Method;

use App\Clients\Asaas;
use App\Dtos\Asaas\PaymentRequest;
use App\Supports\Result;

abstract class AbstractPaymentMethod
{
    protected ?Asaas $client = null;

    public function __construct(protected readonly PaymentRequest $request)
    {

    }

    public function setAsaasClient(Asaas $client): self
    {
        $this->client = $client;

        return $this;
    }

    abstract public function pay(): Result;
}
