<?php

declare(strict_types=1);

use App\Repositories\Interfaces\AsaasRepositoryInterface;
use App\Services\AsaasService;
use App\Supports\Result;

it('should create a new user with service', function () {
    $clientDtoRequest = App\Dtos\Asaas\Client::fromArray(['name' => 'Test', 'cpfCnpj' => '1234567899']);
    $clientDtoResponse = App\Dtos\Asaas\Client::fromArray(['name' => 'Test', 'cpfCnpj' => '1234567899', 'id' => 'cus_109384jer']);
    $repository = Mockery::mock(AsaasRepositoryInterface::class)
        ->shouldReceive('newClient')->andReturn(Result::success($clientDtoResponse))->getMock();

    $repository = new AsaasService($repository);
    $response = $repository->newClient($clientDtoRequest);
    expect($response->isSuccess())->toBeTrue();
    expect($response->getContent()->id)->not->toBeNull();
});
