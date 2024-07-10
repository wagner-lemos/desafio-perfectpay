<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Dtos\Asaas\Client;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use LaravelLegends\PtBrValidator\Rules\CpfOuCnpj;

class NewClientAsaasRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'cpfCnpj' => ['required', new CpfOuCnpj()],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'O nome e obrigatorio',
            'cpfCnpj.required' => 'O document e obrigatorio',
            'name.cpf_ou_cnpj' => 'O document nao e valido',
        ];
    }

    public function toDto(): Client
    {
        return Client::fromArray($this->validated());
    }
}
