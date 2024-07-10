<?php

declare(strict_types=1);

namespace App\Services;

use App\Dtos\Asaas\Client;
use App\Repositories\Interfaces\AsaasRepositoryInterface;
use App\Supports\Result;

class AsaasService
{
    public function __construct(private readonly AsaasRepositoryInterface $repository)
    {

    }

    public function newClient(Client $client): Result
    {
        return $this->repository->newClient($client);
    }
}
