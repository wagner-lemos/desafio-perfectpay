<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductService
{
    public function __construct(private readonly ProductRepositoryInterface $repository)
    {

    }

    public function getAllProducts(): JsonResource
    {
        return $this->repository->getAllProducts();
    }

    public function find($id): Product
    {
        return $this->repository->find($id);
    }
}
