<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PosSelectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => 'required|numeric|min:0.01',
            'installment' => 'required|integer|min:1|max:12',
            'currency' => 'required|string|in:TRY,USD,EUR',
            'card_type' => 'required|string|in:credit,debit,unknown',
            'card_brand' => 'nullable|string'
        ];
    }

    public function messages(): array
    {
        return [
            'amount.required' => 'Ödeme tutarı zorunludur',
            'installment.in' => 'Taksit sayısı 1-12 arasında olmalıdır',
            'currency.in' => 'Para birimi TRY, USD veya EUR olmalıdır',
            'card_type.in' => 'Kart tipi credit, debit veya unknown olmalıdır'
        ];
    }
}
