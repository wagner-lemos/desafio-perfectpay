<?php

declare(strict_types=1);

use App\Clients\Asaas;
use App\Clients\Asaas\Method\Responses\PixResponse;
use App\Clients\Asaas\Method\Responses\TicketResponse;
use App\Dtos\Asaas\CardInfo;
use App\Dtos\Asaas\Client;
use App\Dtos\Asaas\PaymentResponse;
use App\Enums\PaymentMethodEnum;
use App\Enums\PaymentStatusEnum;
use App\Exceptions\AsaasException;
use App\Models\Product;
use App\Repositories\PaymentRepository;
use App\Services\PaymentService;
use App\Supports\AsaasMapper;
use App\Supports\Result;

it('should create new payment using service', function () {
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

    $paymentResponse = App\Dtos\Asaas\PaymentResponse::fromArray($response);

    $repository = Mockery::mock(PaymentRepository::class)
        ->shouldReceive('requestPayment')->andReturn(Result::success($paymentResponse))->getMock();

    $repository->shouldReceive('pay')
        ->andReturn(Result::success(new PixResponse(['encodedImage' => '123', 'payload' => '456'])));

    $service = new PaymentService($repository);
    $client = Client::fromArray(['name' => 'test', 'cpfCnpj' => '123', 'id' => '123']);
    $productModelMock = Mockery::mock(Product::class)->shouldReceive('getAttribute')
        ->with('price')->andReturn(23.4)->getMock();

    $response = $service->pay($client, $productModelMock, PaymentMethodEnum::PIX);
    expect($response->isSuccess())->toBeTrue();
    expect($response->getContent()->getResult())->toBeString()->not->toBeNull();

});

it('should got error with wrong customer', function () {

    $repository = Mockery::mock(PaymentRepository::class)
        ->shouldReceive('requestPayment')->andReturn(Result::failure(new AsaasException('Customer inválido ou não informado.')))->getMock();

    $service = new PaymentService($repository);

    $client = Client::fromArray(['name' => 'test', 'cpfCnpj' => '123', 'id' => '123']);
    $productModelMock = Mockery::mock(Product::class)->shouldReceive('getAttribute')
        ->with('price')->andReturn(23.4)->getMock();

    $response = $service->pay($client, $productModelMock, PaymentMethodEnum::PIX);

    expect($response->isError())->toBeTrue();
    expect($response->getContent()->getMessage())->toBe('Customer inválido ou não informado.');

});

it('should pay with pix', function () {

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

    $repository = Mockery::mock(PaymentRepository::class)
        ->shouldReceive('pay')->andReturn(Result::success(new PixResponse(['encodedImage' => '123', 'payload' => '456'])))->getMock();

    $repository
        ->shouldReceive('requestPayment')->andReturn(Result::success(PaymentResponse::fromArray($response)))->getMock();

    $service = new PaymentService($repository);

    $client = Client::fromArray(['name' => 'test', 'cpfCnpj' => '123', 'id' => '123']);
    $productModelMock = Mockery::mock(Product::class)->shouldReceive('getAttribute')
        ->with('price')->andReturn(23.4)->getMock();

    $response = $service->pay($client, $productModelMock, PaymentMethodEnum::PIX);

    expect($response->isSuccess())->toBeTrue();
    expect($response->getContent()->getResult())->toBe('123@456');

});
it('should pay with ticket', function () {

    $response = [
        'id' => 'test_id',
        'customer' => 'cus_10923k',
        'value' => 100,
        'netValue' => 91.04,
        'billingType' => PaymentMethodEnum::TICKET->value,
        'status' => PaymentStatusEnum::PENDING->value,
        'dueDate' => '2024-05-06',
        'originalDueDate' => '2024-05-06',
        'invoiceUrl' => 'http://localhost/',
        'bankSlipUrl' => 'http://localhost/',
        'invoiceNumber' => '12345',
        'externalReference' => null,
    ];

    $repository = Mockery::mock(PaymentRepository::class)
        ->shouldReceive('pay')->andReturn(Result::success(new TicketResponse(['bankSlipUrl' => '123'])))->getMock();

    $repository
        ->shouldReceive('requestPayment')->andReturn(Result::success(PaymentResponse::fromArray($response)))->getMock();

    $service = new PaymentService($repository);

    $client = Client::fromArray(['name' => 'test', 'cpfCnpj' => '123', 'id' => '123']);
    $productModelMock = Mockery::mock(Product::class)->shouldReceive('getAttribute')
        ->with('price')->andReturn(23.4)->getMock();

    $response = $service->pay($client, $productModelMock, PaymentMethodEnum::TICKET);

    expect($response->isSuccess())->toBeTrue();
    expect($response->getContent()->getResult())->toBe('http://localhost/');

});

it('should pay with credicard', function () {

    $response = [
        'id' => 'test_id',
        'customer' => 'cus_10923k',
        'value' => 100,
        'netValue' => 91.04,
        'billingType' => PaymentMethodEnum::TICKET->value,
        'status' => PaymentStatusEnum::PENDING->value,
        'dueDate' => '2024-05-06',
        'originalDueDate' => '2024-05-06',
        'invoiceUrl' => 'http://localhost/',
        'invoiceNumber' => '12345',
        'externalReference' => null,
    ];
    $creditCardMock = Mockery::mock(Asaas\Method\CreditCard::class)
        ->shouldReceive('pay')->andReturn(Result::success(new Asaas\Method\Responses\CreditCardResponse(['status' => PaymentStatusEnum::CONFIRMED->name])))->getMock();

    $paymentAction = Mockery::mock(Asaas\Payment::class)
        ->shouldReceive('makePayment')->andReturn($creditCardMock)->getMock();

    $paymentAction
        ->shouldReceive('requestNewPayment')->andReturn(Result::success(PaymentResponse::fromArray($response)))->getMock();

    $client = Mockery::mock(Asaas::class)
        ->shouldReceive('payment')->andReturn($paymentAction)->getMock();

    $modelMock = Mockery::mock(\App\Models\PaymentResponse::class)
        ->shouldReceive('create')->andReturn(['id' => 1])->getMock();

    $cardInfoMock = Mockery::mock(CardInfo::class)
        ->shouldReceive('toArray')->andReturn(['creditCard' => [], 'creditCardHolderInfo' => []])->getMock();

    $repository = new PaymentRepository($client, $modelMock, new AsaasMapper());

    $service = new PaymentService($repository);

    $client = Client::fromArray(['name' => 'test', 'cpfCnpj' => '123', 'id' => '123']);
    $productModelMock = Mockery::mock(Product::class)->shouldReceive('getAttribute')
        ->with('price')->andReturn(23.4)->getMock();

    $response = $service->setCardInfo($cardInfoMock)->pay($client, $productModelMock, PaymentMethodEnum::CREDIT_CARD);

    expect($response->isSuccess())->toBeTrue();
    expect($response->getContent()->getResult())->toBe('CONFIRMED');

});
