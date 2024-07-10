<?php

declare(strict_types=1);

use App\Clients\Asaas as AsaasClient;
use App\Dtos\Asaas\Client as DtoClient;
use App\Supports\Result;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Response;

it('should create a Asaas client to api', function () {

    $mockResponse = Mockery::mock(Response::class);
    $mockResponse->shouldReceive('getStatusCode')->andReturn(200)->getMock();
    $mockResponse->shouldReceive('getBody->getContents')->andReturn(json_encode(['name' => 'Test', 'cpfCnpj' => '1234567844', 'id' => 'cus_1029381023']));
    $mockClient = Mockery::mock(GuzzleClient::class);
    $mockClient->shouldReceive('post')
        ->andReturn($mockResponse);

    $dtoMock = Mockery::mock(DtoClient::class)
        ->shouldReceive('toArray')->andReturn(['name' => 'Test', 'cpfCnpj' => '1234567844'])->getMock();

    $client = new AsaasClient($mockClient);
    $response = $client->client()->newClient($dtoMock);

    expect($response)->toBeInstanceOf(Result::class);
    expect($response->isSuccess())->toBeTrue();
    expect($response->getContent())->toHaveProperty('id');
    expect($response->getContent()->toArray()['id'])->not->toBeNull();

});

it('should get error on wrong token', function () {

    $mockResponse = Mockery::mock(Response::class);
    $mockResponse->shouldReceive('getStatusCode')->andReturn(401)->getMock();
    $mockResponse->shouldReceive('getBody->getContents')->andReturn('Unauthorized');
    $mockClient = Mockery::mock(GuzzleClient::class);
    $mockClient->shouldReceive('post')
        ->andReturn($mockResponse);

    $dtoMock = Mockery::mock(DtoClient::class)
        ->shouldReceive('toArray')->andReturn(['name' => 'Test', 'cpfCnpj' => '1234567844'])->getMock();

    $client = new AsaasClient($mockClient);
    $response = $client->client()->newClient($dtoMock);

    expect($response)->toBeInstanceOf(Result::class);
    expect($response->isError())->toBeTrue();
    expect($response->getContent()->getMessage())->toBe('Unauthorized');

});

it('should get error on wrong document', function () {

    $error = [
        'errors' => [
            [
                'code' => 'invalid_cpfCnpj',
                'description' => 'O CPF ou CNPJ informado é inválido.',
            ],
        ],
    ];

    $mockResponse = Mockery::mock(Response::class);
    $mockResponse->shouldReceive('getStatusCode')->andReturn(400)->getMock();
    $mockResponse->shouldReceive('getBody->getContents')->andReturn(json_encode($error));
    $mockClient = Mockery::mock(GuzzleClient::class);
    $mockClient->shouldReceive('post')
        ->andReturn($mockResponse);

    $dtoMock = Mockery::mock(DtoClient::class)
        ->shouldReceive('toArray')->andReturn(['name' => 'Test', 'cpfCnpj' => ''])->getMock();

    $client = new AsaasClient($mockClient);
    $response = $client->client()->newClient($dtoMock);

    expect($response)->toBeInstanceOf(Result::class);
    expect($response->isError())->toBeTrue();
    expect($response->getContent()->getMessage())->toBe('O CPF ou CNPJ informado é inválido.');

});

it('should get error on wrong name', function () {

    $error = [
        'errors' => [
            [
                'code' => 'invalid_name',
                'description' => 'O nome informado é inválido.',
            ],
        ],
    ];

    $mockResponse = Mockery::mock(Response::class);
    $mockResponse->shouldReceive('getStatusCode')->andReturn(400)->getMock();
    $mockResponse->shouldReceive('getBody->getContents')->andReturn(json_encode($error));
    $mockClient = Mockery::mock(GuzzleClient::class);
    $mockClient->shouldReceive('post')
        ->andReturn($mockResponse);

    $dtoMock = Mockery::mock(DtoClient::class)
        ->shouldReceive('toArray')->andReturn(['name' => '', 'cpfCnpj' => '1234567844'])->getMock();

    $client = new AsaasClient($mockClient);
    $response = $client->client()->newClient($dtoMock);

    expect($response)->toBeInstanceOf(Result::class);
    expect($response->isError())->toBeTrue();
    expect($response->getContent()->getMessage())->toBe('O nome informado é inválido.');

});
