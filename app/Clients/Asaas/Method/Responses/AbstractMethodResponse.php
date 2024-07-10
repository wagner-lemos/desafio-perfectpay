<?php

namespace App\Clients\Asaas\Method\Responses;

abstract class AbstractMethodResponse
{
    public function __construct(protected readonly array $response)
    {

    }

    abstract public function getResult(): string;
}
