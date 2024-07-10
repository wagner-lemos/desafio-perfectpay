<?php

declare(strict_types=1);

namespace App\Supports;

use App\Enums\PaymentMethodEnum;
use App\Supports\Interfaces\MapperInterface;

class AsaasMapper implements MapperInterface
{
    public function toPersistence(array $data): array
    {
        return [
            ...$data,
            'response_id' => $data['id'],
            'net_value' => $data['netValue'],
            'billing_type' => $data['billingType'],
            'due_date' => $data['dueDate'],
            'original_due_date' => $data['originalDueDate'],
            'invoice_url' => $data['invoiceUrl'],
            'invoice_number' => $data['invoiceNumber'],
            'audit_log' => $data['audit'],
        ];
    }

    public function translateBillingType(string $billingType): string
    {
        return match ($billingType) {
            PaymentMethodEnum::PIX->value => 'PIX',
            PaymentMethodEnum::TICKET->value => 'BOLETO',
            PaymentMethodEnum::CREDIT_CARD->value => 'CREDIT_CARD'
        };
    }
}
