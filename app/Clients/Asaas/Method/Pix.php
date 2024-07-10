<?php

declare(strict_types=1);

namespace App\Clients\Asaas\Method;

use App\Clients\Asaas\Method\Responses\PixResponse;
use App\Exceptions\AsaasException;
use App\Supports\Result;

class Pix extends AbstractPaymentMethod
{
    public function pay(): Result
    {
        $response = $this->client->getClient()->post(sprintf('/api/v3/payments/%s/pixQrCode', $this->request->id),['verify' => false]);
        if ($response->getStatusCode() == 200) {
            return Result::success(new PixResponse(json_decode($response->getBody()->getContents(), true)));
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
