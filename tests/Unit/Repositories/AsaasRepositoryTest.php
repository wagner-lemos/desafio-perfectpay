<?php

declare(strict_types=1);

use App\Clients\Asaas;
use App\Clients\Asaas\Client;
use App\Exceptions\AsaasException;
use App\Repositories\AsaasRepository;
use App\Supports\Result;

it('should create new client using repository', function () {
    $clientDtoRequest = App\Dtos\Asaas\Client::fromArray(['name' => 'Test', 'cpfCnpj' => '1234567899']);
    $clientDtoResponse = App\Dtos\Asaas\Client::fromArray(['name' => 'Test', 'cpfCnpj' => '1234567899', 'id' => 'cus_109384jer']);
    $clientAction = Mockery::mock(Client::class)
        ->shouldReceive('newClient')->andReturn(Result::success($clientDtoResponse))->getMock();

    $client = Mockery::mock(Asaas::class)
        ->shouldReceive('client')->andReturn($clientAction)->getMock();

    $repository = new AsaasRepository($client);
    $response = $repository->newClient($clientDtoRequest);
    expect($response->isSuccess())->toBeTrue();
    expect($response->getContent()->id)->not->toBeNull();

});

it('should got error with wrong document', function () {
    $clientDtoRequest = App\Dtos\Asaas\Client::fromArray(['name' => 'Test', 'cpfCnpj' => '']);

    $clientAction = Mockery::mock(Client::class)
        ->shouldReceive('newClient')->andReturn(Result::failure(new AsaasException('O CPF e invalido')))->getMock();

    $client = Mockery::mock(Asaas::class)
        ->shouldReceive('client')->andReturn($clientAction)->getMock();

    $repository = new AsaasRepository($client);
    $response = $repository->newClient($clientDtoRequest);
    expect($response->isError())->toBeTrue();
    expect($response->getContent()->getMessage())->toBe('O CPF e invalido');

});
