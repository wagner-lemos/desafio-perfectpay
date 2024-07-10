<?php

declare(strict_types=1);

namespace App\Enums;

enum PaymentMethodEnum: string
{
    case PIX = 'PIX';
    case TICKET = 'BOLETO';
    case CREDIT_CARD = 'CREDIT_CARD';
}
