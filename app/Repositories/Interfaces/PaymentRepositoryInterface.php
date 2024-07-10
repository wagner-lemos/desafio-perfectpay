<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Dtos\Asaas\CardInfo;
use App\Dtos\Asaas\PaymentRequest;
use App\Supports\Result;

interface PaymentRepositoryInterface
{
    public function requestPayment(PaymentRequest $request): Result;

    public function pay(PaymentRequest $request): Result;

    public function setCardInfo(CardInfo $cardInfo): self;
}
