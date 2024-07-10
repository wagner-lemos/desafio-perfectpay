<?php

declare(strict_types=1);

use App\Clients\Asaas;
use App\Clients\Asaas\Method\Responses\TicketResponse;
use App\Clients\Asaas\Method\Ticket;
use App\Dtos\Asaas\PaymentRequest;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

it('should get identification field ticket', function () {
    $response = [
        'identificationField' => '00190000090275928800021932978170187890000005000',
        'nossoNumero' => '6543',
        'barCode' => '00191878900000050000000002759288002193297817',

    ];
    $mockResponse = Mockery::mock(Response::class);
    $mockResponse->shouldReceive('getStatusCode')->andReturn(200)->getMock();
    $mockResponse->shouldReceive('getBody->getContents')->andReturn(json_encode($response));
    $mockClient = Mockery::mock(Client::class);
    $mockClient->shouldReceive('post')
        ->andReturn($mockResponse);
    $request = PaymentRequest::fromArray([
        'customer' => 'customer',
        'billingType' => 'BOLETO',
        'value' => 23.3,
        'dueDate' => '2024-05-06',
    ]);
    $clientMock = Mockery::mock(Asaas::class)
        ->shouldReceive('getClient')->andReturn($mockClient)->getMock();
    $pix = new Ticket($request);
    $pix->setAsaasClient($clientMock);
    expect($pix->pay()->getContent())->toBeInstanceOf(TicketResponse::class);

});
