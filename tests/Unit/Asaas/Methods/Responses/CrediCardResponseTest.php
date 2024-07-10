<?php

declare(strict_types=1);

use App\Clients\Asaas\Method\Responses\CreditCardResponse;
use App\Enums\PaymentStatusEnum;

it('should confirm credicard response', function () {

    $response = ['status' => PaymentStatusEnum::CONFIRMED->name];

    $creditCardResponse = new CreditCardResponse($response);

    expect($creditCardResponse->getResult())->toBe('CONFIRMED');

});
