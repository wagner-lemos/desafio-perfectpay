<?php

declare(strict_types=1);

use App\Clients\Asaas;
use App\Clients\Asaas\Method\CreditCard;
use App\Clients\Asaas\Method\Responses\CreditCardResponse;
use App\Dtos\Asaas\CardInfo;
use App\Dtos\Asaas\PaymentRequest;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

it('should get payment with credit card', function () {
    $response = [
        'object' => 'payment',
        'id' => 'pay_080225913252',
        'dateCreated' => '2017-03-10',
        'customer' => 'cus_G7Dvo4iphUNk',
        'paymentLink' => null,
        'dueDate' => '2017-06-10',
        'value' => 100,
        'netValue' => 95,
        'billingType' => 'CREDIT_CARD',
        'canBePaidAfterDueDate' => true,
        'pixTransaction' => null,
        'status' => 'CONFIRMED',
        'description' => 'Pedido 056984',
        'externalReference' => '056984',
    ];
    $mockResponse = Mockery::mock(Response::class);
    $mockResponse->shouldReceive('getStatusCode')->andReturn(200)->getMock();
    $mockResponse->shouldReceive('getBody->getContents')->andReturn(json_encode($response));
    $mockClient = Mockery::mock(Client::class);
    $mockClient->shouldReceive('post')
        ->andReturn($mockResponse);
    $request = PaymentRequest::fromArray([
        'customer' => 'customer',
        'billingType' => 'CREDIT_CARD',
        'value' => 23.3,
        'dueDate' => '2024-05-06',
    ]);
    $clientMock = Mockery::mock(Asaas::class)
        ->shouldReceive('getClient')->andReturn($mockClient)->getMock();

    $cardInfoMock = Mockery::mock(CardInfo::class)
        ->shouldReceive('toArray')->andReturn(['creditCard' => [], 'creditCardHolderInfo' => []])->getMock();
    $pix = new CreditCard($request, $cardInfoMock);
    $pix->setAsaasClient($clientMock);
    expect($pix->pay()->getContent())->toBeInstanceOf(CreditCardResponse::class);

});
