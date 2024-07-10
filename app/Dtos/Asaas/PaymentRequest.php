<?php

declare(strict_types=1);

namespace App\Dtos\Asaas;

use App\Dtos\AbstractDto;

class PaymentRequest extends AbstractDto
{
    public function __construct(
        public readonly string $customer,
        public readonly string $billingType,
        public readonly float $value,
        public readonly string $dueDate,
        public readonly ?string $id,
    ) {

    }

    public static function fromArray(array $data): static
    {
        return new static(
            customer: $data['customer'],
            billingType: $data['billingType'],
            value: $data['value'],
            dueDate: $data['dueDate'],
            id: $data['id'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'customer' => $this->customer,
            'billingType' => $this->billingType,
            'value' => $this->value,
            'dueDate' => $this->dueDate,
            'id' => $this->id,
        ];
    }
}
