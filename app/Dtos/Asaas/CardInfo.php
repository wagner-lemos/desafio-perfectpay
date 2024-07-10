<?php

declare(strict_types=1);

namespace App\Dtos\Asaas;

use App\Dtos\AbstractDto;

class CardInfo extends AbstractDto
{
    public function __construct(
        public readonly HolderCard $holderCard,
        public readonly HolderInfo $holderInfo
    ) {

    }

    public static function fromArray(array $data): static
    {
        return new static(
            holderCard: $data['holderCard'],
            holderInfo: $data['holderInfo']
        );
    }

    public function toArray(): array
    {
        return [
            'creditCard' => $this->holderCard->toArray(),
            'creditCardHolderInfo' => $this->holderInfo->toArray(),
        ];
    }
}
