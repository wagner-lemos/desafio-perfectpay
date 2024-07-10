<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Dtos\Asaas\Client;
use App\Supports\Result;

interface AsaasRepositoryInterface
{
    public function newClient(Client $client): Result;
}
