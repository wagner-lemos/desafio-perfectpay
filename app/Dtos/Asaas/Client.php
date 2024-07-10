<?php

declare(strict_types=1);

namespace App\Dtos\Asaas;

use App\Dtos\AbstractDto;

class Client extends AbstractDto
{
    public function __construct(
        public readonly string $cpfCnpj,
        public readonly string $name,
        public readonly ?string $object,
        public readonly ?string $id,
        public readonly ?string $dateCreated,
        public readonly ?string $email,
        public readonly ?string $phone,
        public readonly ?string $mobilePhone,
        public readonly ?string $address,
        public readonly ?string $addressNumber,
        public readonly ?string $complement,
        public readonly ?string $province,
        public readonly ?string $postalCode,
        public readonly ?string $personType,
        public readonly ?string $aditionalEmails,
        public readonly ?string $externalReference,
        public readonly ?bool $notificationDisabled,
        public readonly ?int $city,
        public readonly ?string $state,
        public readonly ?string $country,
        public readonly ?string $observations,
    ) {

    }

    public static function fromArray(array $data): static
    {
        return new static(
            cpfCnpj: $data['cpfCnpj'],
            name: $data['name'],
            object: $data['object'] ?? null,
            id: $data['id'] ?? null,
            dateCreated: $data['dateCreated'] ?? null,
            email: $data['email'] ?? null,
            phone: $data['phone'] ?? null,
            mobilePhone: $data['mobilePhone'] ?? null,
            address: $data['address'] ?? null,
            addressNumber: $data['addressNumber'] ?? null,
            complement: $data['complement'] ?? null,
            province: $data['province'] ?? null,
            postalCode: $data['postalCode'] ?? null,
            personType: $data['personType'] ?? null,
            aditionalEmails: $data['aditionalEmails'] ?? null,
            externalReference: $data['externalReference'] ?? null,
            notificationDisabled: $data['notificationDisabled'] ?? null,
            city: $data['city'] ?? null,
            state: $data['state'] ?? null,
            country: $data['country'] ?? null,
            observations: $data['observations'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'object' => $this->object,
            'name' => $this->name,
            'cpfCnpj' => $this->cpfCnpj,
            'dateCreated' => $this->dateCreated,
            'email' => $this->email,
            'phone' => $this->phone,
            'mobilePhone' => $this->mobilePhone,
            'address' => $this->address,
            'addressNnumber' => $this->addressNumber,
            'complement' => $this->complement,
            'province' => $this->province,
            'postalCode' => $this->postalCode,
            'personType' => $this->personType,
            'aditionalEmails' => $this->aditionalEmails,
            'externalReference' => $this->externalReference,
            'notificationDisabled' => $this->notificationDisabled,
            'city' => $this->city,
            'state' => $this->state,
            'country' => $this->country,
            'observations' => $this->observations,
        ];
    }
}
