<?php

declare(strict_types=1);

namespace App\Supports\Interfaces;

interface MapperInterface
{
    public function toPersistence(array $data): array;
}
