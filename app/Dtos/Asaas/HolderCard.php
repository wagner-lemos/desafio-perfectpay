<?php

declare(strict_types=1);

namespace App\Dtos\Asaas;

use App\Dtos\AbstractDto;

class HolderCard extends AbstractDto
{
    public function __construct(
        public readonly string $holderName,
        public readonly string $number,
        public readonly string $expiryMonth,
        public readonly string $expiryYear,
        public readonly string $ccv,
    ) {

    }

    public static function fromArray(array $data): static
    {
        return new static(
            holderName: $data['holderName'],
            number: $data['number'],
            expiryMonth: $data['expiryMonth'],
            expiryYear: $data['expiryYear'],
            ccv: $data['ccv']
        );
    }

    public function toArray(): array
    {
        return [
            'holderName' => $this->holderName,
            'number' => $this->number,
            'expiryMonth' => $this->expiryMonth,
            'expiryYear' => $this->expiryYear,
            'ccv' => $this->ccv,
        ];
    }
}
