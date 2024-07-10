<?php

declare(strict_types=1);

namespace App\Clients\Asaas;

use App\Clients\Asaas;
use App\Dtos\Asaas\Client as AsaasClient;
use App\Exceptions\AsaasException;
use App\Supports\Result;

class Client implements ActionInterface
{
    public function __construct(private readonly Asaas $asaas)
    {

    }

    public function newClient(AsaasClient $asaasClient): Result
    {
        $response = $this->asaas->getClient()->post('/api/v3/customers', ['form_params' => $asaasClient->toArray(), 'verify' => false]);
        if ($response->getStatusCode() == 200) {
            return Result::success(AsaasClient::fromArray(json_decode($response->getBody()->getContents(), true)));
        }
		
        if ($response->getStatusCode() == 400) {
            $error = json_decode($response->getBody()->getContents(), true);

            return Result::failure(new AsaasException($error['errors'][0]['description']));
        }
        if ($response->getStatusCode() == 401) {
            return Result::failure(new AsaasException('Unauthorized'));
        }

        return Result::failure(new AsaasException('Unknow'));
    }
}
