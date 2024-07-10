<?php

declare(strict_types=1);

namespace App\Clients\Asaas;

use App\Clients\Asaas;
use App\Clients\Asaas\Method\AbstractPaymentMethod;
use App\Clients\Asaas\Method\MethodFactory;
use App\Dtos\Asaas\CardInfo;
use App\Dtos\Asaas\PaymentRequest;
use App\Dtos\Asaas\PaymentResponse;
use App\Exceptions\AsaasException;
use App\Supports\Result;

class Payment implements ActionInterface
{
    public function __construct(private readonly Asaas $asaas)
    {

    }

    public function requestNewPayment(PaymentRequest $request): Result
    {
        $response = $this->asaas->getClient()->post('/api/v3/payments', ['form_params' => $request->toArray(), 'verify' => false]);
        if ($response->getStatusCode() == 200) {
            $audit = $response->getBody()->getContents();

            return Result::success(PaymentResponse::fromArray(json_decode($audit, true) + ['audit' => $audit]));
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

    public function makePayment(PaymentRequest $request, ?CardInfo $cardInfo = null): AbstractPaymentMethod
    {
        return MethodFactory::make($request, $cardInfo)->setAsaasClient($this->asaas);
    }
}
