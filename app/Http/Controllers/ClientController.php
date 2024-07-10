<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\NewClientAsaasRequest;
use App\Services\AsaasService;
use App\Supports\Result;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function __construct(private readonly AsaasService $service)
    {

    }

    public function newClientForm(Request $request): View
    {
        return view('newclient');
    }

    public function newClientRequest(NewClientAsaasRequest $request): RedirectResponse
    {
        $result = $this->service->newClient($request->toDto());
        $request->session()->put('result_new_client', $result);

        return $this->handleResult($result);
    }

    private function handleResult(Result $result): RedirectResponse
    {
        $redirect = redirect()->route('products');
        if ($result->isSuccess()) {
            return $redirect->with('result', 'success')->with('content', null);
        }

        return $redirect->with('result', 'error')->with('content', $result->getContent()->getMessage());
    }
}
