<?php

declare(strict_types=1);

use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Http\Resources\Json\JsonResource;

it('should return a product collection', function () {
    $productModel = Mockery::mock(Product::class);
    $productModel->shouldReceive('all')->andReturn([$productModel])->getMock();
    $productRepository = new ProductRepository($productModel);
    expect($productRepository->getAllProducts())->toBeInstanceOf(JsonResource::class);
    expect($productRepository->getAllProducts())->toHaveCount(1);
});
