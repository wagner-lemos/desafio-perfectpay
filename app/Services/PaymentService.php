<?php

declare(strict_types=1);

namespace App\Services;

use App\Clients\Asaas\Method\Responses\TicketResponse;
use App\Dtos\Asaas\CardInfo;
use App\Dtos\Asaas\Client;
use App\Dtos\Asaas\PaymentRequest;
use App\Enums\PaymentMethodEnum;
use App\Models\Product;
use App\Repositories\Interfaces\PaymentRepositoryInterface;
use App\Supports\Result;

class PaymentService
{
    private ?CardInfo $cardInfo = null;

    public function __construct(private readonly PaymentRepositoryInterface $repository)
    {

    }

    public function pay(Client $client, Product $product, PaymentMethodEnum $paymentMethod): Result
    {
        $paymentRequest = PaymentRequest::fromArray(['dueDate' => now()->format('Y-m-d'), 'customer' => $client->id, 'value' => $product->price, 'billingType' => $paymentMethod->value]);
        $paymentRequested = $this->repository->requestPayment($paymentRequest);
        if ($paymentRequested->isError()) {
            return $paymentRequested;
        }
        if ($paymentMethod == PaymentMethodEnum::TICKET) {
            return Result::success(new TicketResponse($paymentRequested->getContent()->toArray()));
        }
        $paymentRequest = PaymentRequest::fromArray([...$paymentRequest->toArray(), 'id' => $paymentRequested->getContent()->id]);
        if ($this->cardInfo) {
            return $this->repository->setCardInfo($this->cardInfo)->pay($paymentRequest);
        }

        return $this->repository->pay($paymentRequest);
    }

    public function setCardInfo(CardInfo $cardInfo): self
    {
        $this->cardInfo = $cardInfo;

        return $this;
    }
}
