<?php

declare(strict_types=1);

namespace App\Clients;

use App\Clients\Asaas\ActionInterface;
use App\Clients\Asaas\Client;
use App\Clients\Asaas\Payment;
use GuzzleHttp\Client as GuzzleClient;

/**
 * @method Client client(Client $client)
 * @method Payment payment()
 */
class Asaas implements HttpClientInterface
{
    public function __construct(private readonly GuzzleClient $client)
    {

    }

    public function getClient(): ?GuzzleClient
    {
        return $this->client;
    }

    public function getUrl(string $environment): ?string
    {
        return $this->url[$environment] ?? null;
    }

    public function __call($method, $args): ActionInterface
    {
        return match ($method) {
            'client' => new Client($this),
            'payment' => new Payment($this),
        };
    }
}
