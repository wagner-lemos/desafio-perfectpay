<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductRepository implements ProductRepositoryInterface
{
    public function __construct(private readonly Product $product)
    {

    }

    public function getAllProducts(): JsonResource
    {
        return ProductResource::collection($this->product->all());
    }

    public function find($id): Model
    {
        return $this->product->find($id);
    }
}
