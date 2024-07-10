<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Clients\Asaas\Method\Responses\CreditCardResponse;
use App\Clients\Asaas\Method\Responses\PixResponse;
use App\Clients\Asaas\Method\Responses\TicketResponse;
use App\Enums\PaymentMethodEnum;
use App\Http\Requests\CreditCardRequest;
use App\Services\PaymentService;
use App\Services\ProductService;
use App\Supports\Result;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(
        private readonly ProductService $productService,
        private readonly PaymentService $paymentService,
    )
    {

    }

    public function pix()
    {
        return view('pix');
    }

    public function ticket()
    {
        return view('ticket');
    }

    public function creditCard()
    {
        return view('credit-card-result');
    }

    public function creditCardForm(Request $request)
    {
        return view('creditcard-form', ['product' => $request->product]);
    }

    public function pay(Request $request): RedirectResponse
    {
        $product = $this->productService->find((int)$request->product);
        $client = $request->session()->get('result_new_client')->getContent();
        $result = $this->paymentService->pay($client, $product, PaymentMethodEnum::tryFrom($request->billingType));

        return $this->handleResult($result);
    }

    public function creditCardProcess(CreditCardRequest $request)
    {
        $product = $this->productService->find($request->product);
        $client = $request->session()->get('result_new_client')->getContent();
        $result = $this->paymentService
            ->setCardInfo($request->toDto())
            ->pay($client, $product, PaymentMethodEnum::tryFrom($request->billingType));

        return $this->handleResult($result);
    }

    private function handleResult(Result $result): RedirectResponse
    {
        $redirect = redirect()->route('products');

        if ($result->isSuccess() && $result->getContent() instanceof PixResponse) {
            [$image, $copy] = explode('@', $result->getContent()->getResult());
            return redirect()->route('pix')->with('image', $image)->with('copy', $copy);
        }
        if ($result->isSuccess() && $result->getContent() instanceof TicketResponse) {
            return redirect()->route('ticket')->with('result_payment', $result->getContent()->getResult());
        }
        if ($result->isSuccess() && $result->getContent() instanceof CreditCardResponse) {
            return redirect()->route('products')->with('result', 'success')->with('content', $result->getContent()->getResult());
        }

        return $redirect->with('result', 'error')->with('content', 'Erro no processamento do pagamento: ' . $result->getContent()->getMessage());
    }
}
