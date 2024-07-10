<?php

declare(strict_types=1);

namespace App\Dtos\Asaas;

use App\Dtos\AbstractDto;

class HolderInfo extends AbstractDto
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $cpfCnpj,
        public readonly string $postalCode,
        public readonly string $addressNumber,
        public readonly string $phone,
        public readonly ?string $addressComplement,
        public readonly ?string $mobilePhone,
    ) {

    }

    public static function fromArray(array $data): static
    {
        return new static(
            name: $data['name'],
            email: $data['email'],
            cpfCnpj: $data['cpfCnpj'],
            postalCode: $data['postalCode'],
            addressNumber: $data['addressNumber'],
            phone: $data['phone'],
            addressComplement: $data['addressComplement'] ?? null,
            mobilePhone: $data['mobilePhone'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'cpfCnpj' => $this->cpfCnpj,
            'postalCode' => $this->postalCode,
            'addressNumber' => $this->addressNumber,
            'addressComplement' => $this->addressComplement,
            'phone' => $this->phone,
            'mobilePhone' => $this->mobilePhone,
        ];
    }
}
