<?php

declare(strict_types=1);

use App\Clients\Asaas\Method\Responses\TicketResponse;

it('should get ticket response', function () {

    $response = [
        'identificationField' => '00190000090275928800021932978170187890000005000',
        'nossoNumero' => '6543',
        'barCode' => '00191878900000050000000002759288002193297817',
    ];

    $ticketResponse = new TicketResponse($response);

    expect($ticketResponse->getResult())->toBeString()->not->toBeNull();
    expect($ticketResponse->getResult())->toBe($response['identificationField']);
});
