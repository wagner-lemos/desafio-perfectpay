<?php

declare(strict_types=1);

namespace App\Clients\Asaas\Method;

use App\Dtos\Asaas\CardInfo;
use App\Dtos\Asaas\PaymentRequest;
use App\Enums\PaymentMethodEnum;

class MethodFactory
{
    public static function make(PaymentRequest $request, ?CardInfo $cardInfo): AbstractPaymentMethod
    {
        return match ($request->billingType) {
            PaymentMethodEnum::PIX->value => new Pix($request),
            PaymentMethodEnum::CREDIT_CARD->value => new CreditCard($request, $cardInfo),
            PaymentMethodEnum::TICKET->value => new Ticket($request)
        };
    }
}
