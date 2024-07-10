<?php

declare(strict_types=1);

use App\Clients\Asaas as AsaasClient;
use App\Dtos\Asaas\CardInfo;
use App\Dtos\Asaas\PaymentRequest;
use App\Enums\PaymentMethodEnum;
use App\Enums\PaymentStatusEnum;
use App\Supports\Result;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Response;

it('should create a Asaas payment to api', function () {

    $response = [
        'id' => 'test_id',
        'customer' => 'cus_10923k',
        'value' => 100,
        'netValue' => 91.04,
        'billingType' => PaymentMethodEnum::PIX->value,
        'status' => PaymentStatusEnum::PENDING->value,
        'dueDate' => '2024-05-06',
        'originalDueDate' => '2024-05-06',
        'invoiceUrl' => 'http://localhost/',
        'invoiceNumber' => '12345',
        'externalReference' => null,
    ];

    $request = [
        'customer' => 'cus_10923k',
        'billingType' => PaymentMethodEnum::PIX->value,
        'value' => 100,
        'dueDate' => '2024-05-06',
    ];

    $mockResponse = Mockery::mock(Response::class);
    $mockResponse->shouldReceive('getStatusCode')->andReturn(200)->getMock();
    $mockResponse->shouldReceive('getBody->getContents')->andReturn(json_encode($response));
    $mockClient = Mockery::mock(GuzzleClient::class);
    $mockClient->shouldReceive('post')
        ->andReturn($mockResponse);

    $dtoMock = Mockery::mock(PaymentRequest::class)
        ->shouldReceive('toArray')->andReturn($request)->getMock();

    $client = new AsaasClient($mockClient);
    $response = $client->payment()->requestNewPayment($dtoMock);

    expect($response)->toBeInstanceOf(Result::class);
    expect($response->isSuccess())->toBeTrue();
    expect($response->getContent())->toHaveProperty('id');
    expect($response->getContent()->toArray()['id'])->not->toBeNull();

});

it('should get error on wrong token', function () {

    $mockResponse = Mockery::mock(Response::class);
    $mockResponse->shouldReceive('getStatusCode')->andReturn(401)->getMock();

    $request = [
        'customer' => 'cus_10923k',
        'billingType' => PaymentMethodEnum::PIX->value,
        'value' => 100,
        'dueDate' => '2024-05-06',
    ];

    $mockResponse->shouldReceive('getBody->getContents')->andReturn('Unauthorized');
    $mockClient = Mockery::mock(GuzzleClient::class);
    $mockClient->shouldReceive('post')
        ->andReturn($mockResponse);

    $dtoMock = Mockery::mock(PaymentRequest::class)
        ->shouldReceive('toArray')->andReturn($request)->getMock();

    $client = new AsaasClient($mockClient);
    $response = $client->payment()->requestNewPayment($dtoMock);

    expect($response)->toBeInstanceOf(Result::class);
    expect($response->isError())->toBeTrue();
    expect($response->getContent()->getMessage())->toBe('Unauthorized');

});

it('should get error on wrong customer', function () {

    $error = [
        'errors' => [
            [
                'code' => 'invalid_customer',
                'description' => 'Customer inválido ou não informado.',
            ],
        ],
    ];
    $request = [
        'customer' => 'cus_10923k',
        'billingType' => PaymentMethodEnum::PIX->value,
        'value' => 100,
        'dueDate' => '2024-05-06',
    ];

    $mockResponse = Mockery::mock(Response::class);
    $mockResponse->shouldReceive('getStatusCode')->andReturn(400)->getMock();
    $mockResponse->shouldReceive('getBody->getContents')->andReturn(json_encode($error));
    $mockClient = Mockery::mock(GuzzleClient::class);
    $mockClient->shouldReceive('post')
        ->andReturn($mockResponse);

    $dtoMock = Mockery::mock(PaymentRequest::class)
        ->shouldReceive('toArray')->andReturn($request)->getMock();

    $client = new AsaasClient($mockClient);
    $response = $client->payment()->requestNewPayment($dtoMock);

    expect($response)->toBeInstanceOf(Result::class);
    expect($response->isError())->toBeTrue();
    expect($response->getContent()->getMessage())->toBe('Customer inválido ou não informado.');

});

it('should get method payment with pix', function () {

    $request = PaymentRequest::fromArray([
        'customer' => 'dummy_customer',
        'billingType' => 'PIX',
        'value' => 12.4,
        'dueDate' => '2024-05-06',
    ]);
    $mockClient = Mockery::mock(GuzzleClient::class);

    $client = new AsaasClient($mockClient);
    $response = $client->payment()->makePayment($request);

    expect($response)->toBeInstanceOf(AsaasClient\Method\Pix::class);
});

it('should get method payment with ticket', function () {

    $request = PaymentRequest::fromArray([
        'customer' => 'dummy_customer',
        'billingType' => 'BOLETO',
        'value' => 12.4,
        'dueDate' => '2024-05-06',
    ]);
    $mockClient = Mockery::mock(GuzzleClient::class);

    $client = new AsaasClient($mockClient);
    $response = $client->payment()->makePayment($request);

    expect($response)->toBeInstanceOf(AsaasClient\Method\Ticket::class);
});

it('should get method payment with creditCard', function () {

    $request = PaymentRequest::fromArray([
        'customer' => 'dummy_customer',
        'billingType' => 'CREDIT_CARD',
        'value' => 12.4,
        'dueDate' => '2024-05-06',
    ]);
    $mockClient = Mockery::mock(GuzzleClient::class);

    $cardInfoMock = Mockery::mock(CardInfo::class)
        ->shouldReceive('toArray')->andReturn(['creditCard' => [], 'creditCardHolderInfo' => []])->getMock();
    $client = new AsaasClient($mockClient);
    $response = $client->payment()->makePayment($request, $cardInfoMock);

    expect($response)->toBeInstanceOf(AsaasClient\Method\CreditCard::class);
});
