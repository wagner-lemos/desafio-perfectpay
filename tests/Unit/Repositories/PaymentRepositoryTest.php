<?php

declare(strict_types=1);

use App\Clients\Asaas;
use App\Dtos\Asaas\CardInfo;
use App\Enums\PaymentMethodEnum;
use App\Enums\PaymentStatusEnum;
use App\Exceptions\AsaasException;
use App\Models\PaymentResponse;
use App\Repositories\PaymentRepository;
use App\Supports\AsaasMapper;
use App\Supports\Result;

it('should create new payment using repository', function () {
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
    $paymentRequest = App\Dtos\Asaas\PaymentRequest::fromArray($request);
    $paymentResponse = App\Dtos\Asaas\PaymentResponse::fromArray($response);
    $paymentAction = Mockery::mock(Asaas\Payment::class)
        ->shouldReceive('requestNewPayment')->andReturn(Result::success($paymentResponse))->getMock();

    $client = Mockery::mock(Asaas::class)
        ->shouldReceive('payment')->andReturn($paymentAction)->getMock();
    $modelMock = Mockery::mock(PaymentResponse::class)
        ->shouldReceive('create')->andReturn(['id' => 1])->getMock();

    $repository = new PaymentRepository($client, $modelMock, new AsaasMapper());
    $response = $repository->requestPayment($paymentRequest);

    expect($response->isSuccess())->toBeTrue();
    expect($response->getContent()->id)->not->toBeNull();

});

it('should got error with wrong customer', function () {

    $request = [
        'customer' => 'cus_10923k',
        'billingType' => PaymentMethodEnum::PIX->value,
        'value' => 100,
        'dueDate' => '2024-05-06',
    ];
    $paymentRequest = App\Dtos\Asaas\PaymentRequest::fromArray($request);
    $paymentAction = Mockery::mock(Asaas\Payment::class)
        ->shouldReceive('requestNewPayment')->andReturn(Result::failure(new AsaasException('Customer inválido ou não informado.')))->getMock();

    $client = Mockery::mock(Asaas::class)
        ->shouldReceive('payment')->andReturn($paymentAction)->getMock();
    $modelMock = Mockery::mock(PaymentResponse::class)
        ->shouldReceive('create')->andReturn(['id' => 1])->getMock();

    $repository = new PaymentRepository($client, $modelMock, new AsaasMapper());
    $response = $repository->requestPayment($paymentRequest);

    expect($response->isError())->toBeTrue();
    expect($response->getContent()->getMessage())->toBe('Customer inválido ou não informado.');

});

it('should got payment with pix', function () {

    $request = [
        'customer' => 'cus_10923k',
        'billingType' => PaymentMethodEnum::PIX->value,
        'value' => 100,
        'dueDate' => '2024-05-06',
    ];
    $paymentRequest = App\Dtos\Asaas\PaymentRequest::fromArray($request);

    $pixMock = Mockery::mock(Asaas\Method\Pix::class)
        ->shouldReceive('pay')->andReturn(Result::success(new Asaas\Method\Responses\PixResponse(['encodedImage' => '123', 'payload' => '456'])))->getMock();

    $paymentAction = Mockery::mock(Asaas\Payment::class)
        ->shouldReceive('makePayment')->andReturn($pixMock)->getMock();

    $client = Mockery::mock(Asaas::class)
        ->shouldReceive('payment')->andReturn($paymentAction)->getMock();
    $modelMock = Mockery::mock(PaymentResponse::class)
        ->shouldReceive('create')->andReturn(['id' => 1])->getMock();

    $repository = new PaymentRepository($client, $modelMock, new AsaasMapper());
    $response = $repository->pay($paymentRequest);

    expect($response->isSuccess())->toBeTrue();
    expect($response->getContent()->getResult())->not->toBeNull();

});

it('should got payment with ticket', function () {

    $request = [
        'customer' => 'cus_10923k',
        'billingType' => PaymentMethodEnum::TICKET->value,
        'value' => 100,
        'dueDate' => '2024-05-06',
    ];
    $paymentRequest = App\Dtos\Asaas\PaymentRequest::fromArray($request);

    $pixMock = Mockery::mock(Asaas\Method\Pix::class)
        ->shouldReceive('pay')->andReturn(Result::success(new Asaas\Method\Responses\TicketResponse(['bankSlipUrl' => '123'])))->getMock();

    $paymentAction = Mockery::mock(Asaas\Payment::class)
        ->shouldReceive('makePayment')->andReturn($pixMock)->getMock();

    $client = Mockery::mock(Asaas::class)
        ->shouldReceive('payment')->andReturn($paymentAction)->getMock();
    $modelMock = Mockery::mock(PaymentResponse::class)
        ->shouldReceive('create')->andReturn(['id' => 1])->getMock();

    $repository = new PaymentRepository($client, $modelMock, new AsaasMapper());
    $response = $repository->pay($paymentRequest);

    expect($response->isSuccess())->toBeTrue();
    expect($response->getContent()->getResult())->not->toBeNull();

});

it('should get method payment with creditCard', function () {

    $request = [
        'customer' => 'cus_10923k',
        'billingType' => PaymentMethodEnum::CREDIT_CARD->value,
        'value' => 100,
        'dueDate' => '2024-05-06',
    ];
    $paymentRequest = App\Dtos\Asaas\PaymentRequest::fromArray($request);

    $pixMock = Mockery::mock(Asaas\Method\CreditCard::class)
        ->shouldReceive('pay')->andReturn(Result::success(new Asaas\Method\Responses\CreditCardResponse(['status' => PaymentStatusEnum::CONFIRMED->name])))->getMock();

    $paymentAction = Mockery::mock(Asaas\Payment::class)
        ->shouldReceive('makePayment')->andReturn($pixMock)->getMock();

    $client = Mockery::mock(Asaas::class)
        ->shouldReceive('payment')->andReturn($paymentAction)->getMock();

    $modelMock = Mockery::mock(PaymentResponse::class)
        ->shouldReceive('create')->andReturn(['id' => 1])->getMock();

    $cardInfoMock = Mockery::mock(CardInfo::class)
        ->shouldReceive('toArray')->andReturn(['creditCard' => [], 'creditCardHolderInfo' => []])->getMock();

    $repository = new PaymentRepository($client, $modelMock, new AsaasMapper());

    $response = $repository->setCardInfo($cardInfoMock)->pay($paymentRequest);

    expect($response->isSuccess())->toBeTrue();
    expect($response->getContent()->getResult())->not->toBeNull();
});
