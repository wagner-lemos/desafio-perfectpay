<?php

declare(strict_types=1);

use App\Repositories\ProductRepository;
use App\Services\ProductService;
use Illuminate\Http\Resources\Json\JsonResource;

it('should return a product collection', function () {
    $productRepository = Mockery::mock(ProductRepository::class);
    $productRepository->shouldReceive('getAllProducts')->andReturn(JsonResource::collection([$productRepository]))->getMock();
    $productService = new ProductService($productRepository);
    expect($productService->getAllProducts())->toBeInstanceOf(JsonResource::class);
    expect($productService->getAllProducts())->toHaveCount(1);
});
