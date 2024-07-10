<?php

namespace App\Dtos;

abstract class AbstractDto
{
    abstract public static function fromArray(array $data): static;

    abstract public function toArray(): array;
}
