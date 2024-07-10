<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Clients\Asaas;
use App\Dtos\Asaas\CardInfo;
use App\Dtos\Asaas\PaymentRequest;
use App\Repositories\Interfaces\PaymentRepositoryInterface;
use App\Supports\Interfaces\MapperInterface;
use App\Supports\Result;
use Illuminate\Database\Eloquent\Model;

class PaymentRepository implements PaymentRepositoryInterface
{
    private ?CardInfo $cardInfo = null;

    public function __construct(
        private readonly Asaas $client,
        private readonly Model $model,
        private readonly MapperInterface $mapper,
    ) {

    }

    public function requestPayment(PaymentRequest $request): Result
    {
        $result = $this->client->payment()->requestNewPayment($request);
        $this->registerRequestPaymentResponse($result);

        return $result;
    }

    public function pay(PaymentRequest $request): Result
    {
        return $this->client->payment()->makePayment($request, $this->cardInfo)->pay();
    }

    private function registerRequestPaymentResponse(Result $result): void
    {
        if ($result->isSuccess()) {
            $this->model->create($this->mapper->toPersistence($result->getContent()->toArray()));
        }
    }

    public function setCardInfo(CardInfo $cardInfo): self
    {
        $this->cardInfo = $cardInfo;

        return $this;
    }
}
