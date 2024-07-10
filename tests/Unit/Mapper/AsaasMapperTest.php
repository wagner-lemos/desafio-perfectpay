<?php

declare(strict_types=1);

use App\Supports\AsaasMapper;

it('should transform data from response', function () {
    $request = [
        'id' => 'dummy_id',
        'netValue' => 'dummy_net_value',
        'billingType' => 'dummy_billing_type',
        'dueDate' => 'dummy_due_date',
        'originalDueDate' => 'dummy_original_due_date',
        'invoiceUrl' => 'dummy_invoice_url',
        'invoiceNumber' => 'dummy_invoice_number',
        'audit' => json_encode(['name' => 'test']),
    ];

    $mapper = new AsaasMapper();
    $result = $mapper->toPersistence($request);

    expect($result)->toBeArray();
    expect($result['audit_log'])->toBeJson();
    expect($result)->toMatchArray(['response_id' => 'dummy_id']);
});
