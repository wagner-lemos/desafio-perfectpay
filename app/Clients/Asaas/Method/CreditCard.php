<?php

declare(strict_types=1);

namespace App\Clients\Asaas\Method;

use App\Clients\Asaas\Method\Responses\CreditCardResponse;
use App\Dtos\Asaas\CardInfo;
use App\Dtos\Asaas\PaymentRequest;
use App\Exceptions\AsaasException;
use App\Supports\Result;

class CreditCard extends AbstractPaymentMethod
{
    public function __construct(protected readonly PaymentRequest $request, private readonly CardInfo $cardInfo)
    {
    }

    public function pay(): Result
    {
        $response = $this->client->getClient()->post(
            sprintf('/api/v3/payments/%s/payWithCreditCard', $this->request->id),
            [
                'body' => json_encode($this->cardInfo->toArray()),
                'headers' => [
                    'content-type' => 'application/json',
                    'accept' => 'application/json',
                ],
            ]
        );
        if ($response->getStatusCode() == 200) {
            return Result::success(new CreditCardResponse(json_decode($response->getBody()->getContents(), true)));
        }
        if ($response->getStatusCode() == 404) {
            return Result::failure(new AsaasException('Cobranca nao encontrada'));
        }
        if ($response->getStatusCode() == 401) {
            return Result::failure(new AsaasException('Unauthorized'));
        }

        return Result::failure(new AsaasException($response->getBody()->getContents()));
    }
}
