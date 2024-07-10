<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;

interface ProductRepositoryInterface
{
    public function getAllProducts(): JsonResource;

    public function find(int $id): Model;
}
