<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Clients\Asaas;
use App\Dtos\Asaas\Client;
use App\Repositories\Interfaces\AsaasRepositoryInterface;
use App\Supports\Result;

class AsaasRepository implements AsaasRepositoryInterface
{
    public function __construct(private readonly Asaas $client)
    {

    }

    public function newClient(Client $client): Result
    {
        return $this->client->client()->newClient($client);
    }
}
