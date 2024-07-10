<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Dtos\Asaas\CardInfo;
use App\Dtos\Asaas\HolderCard;
use App\Dtos\Asaas\HolderInfo;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use LaravelLegends\PtBrValidator\Rules\CelularComDdd;
use LaravelLegends\PtBrValidator\Rules\CpfOuCnpj;
use LaravelLegends\PtBrValidator\Rules\FormatoCep;

class CreditCardRequest extends FormRequest
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
            'holdercard.holderName' => 'required|min:3',
            'holdercard.number' => 'required|size:16',
            'holdercard.expiryMonth' => 'required|between:1,12',
            'holdercard.expiryYear' => 'required|size:4',
            'holdercard.ccv' => 'required|min:3|max:4',
            'holderinfo.name' => 'required|min:3',
            'holderinfo.email' => 'required|email',
            'holderinfo.postalCode' => ['required', new FormatoCep()],
            'holderinfo.cpfCnpj' => ['required', new CpfOuCnpj()],
            'holderinfo.addressNumber' => 'required', //pode ser um casa sem numero
            'holderinfo.phone' => ['required', new CelularComDdd()],

        ];
    }

    public function toDto()
    {
        return CardInfo::fromArray([
            'holderCard' => HolderCard::fromArray($this->validated()['holdercard']),
            'holderInfo' => HolderInfo::fromArray($this->validated()['holderinfo']),
        ]);
    }
}
