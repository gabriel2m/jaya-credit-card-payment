<?php

namespace App\Http\Requests;

use App\Enums\PayerIdentificationType;
use App\Rules\CPF;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePaymentRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'transaction_amount' => [
                'required',
                'numeric',
                'gt:0',
                'max:999999999',
                'regex:/^\d+(\.\d{1,2})?$/',
            ],
            'installments' => [
                'required',
                'integer',
                'min:1',
                'max:999',
            ],
            'token' => [
                'required',
                'string',
                'alpha_num:ascii',
                'max:255',
            ],
            'payment_method_id' => [
                'required',
                'string',
                'max:255',
            ],
            'payer.*' => [
                'required',
                'array',
            ],
            'payer.email' => [
                'required',
                'string',
                'email',
                'max:255',
            ],
            'payer.identification.type' => [
                'required',
                Rule::enum(PayerIdentificationType::class),
            ],
            'payer.identification.number' => [
                'required',
                Rule::when(
                    $this->enum('payer.identification.type', PayerIdentificationType::class) == PayerIdentificationType::CPF,
                    new CPF
                ),
            ],
        ];
    }
}
