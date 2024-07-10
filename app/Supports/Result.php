<?php

declare(strict_types=1);

namespace App\Supports;

class Result
{
    public function __construct(private readonly bool $isError, private readonly mixed $message)
    {

    }

    public function isSuccess(): bool
    {
        return ! $this->isError;
    }

    public function isError(): bool
    {
        return $this->isError;
    }

    public function getContent(): mixed
    {
        return $this->message;
    }

    public static function failure(mixed $message): static
    {
        return new static(true, $message);
    }

    public static function success(mixed $message): static
    {
        return new static(false, $message);
    }
}
