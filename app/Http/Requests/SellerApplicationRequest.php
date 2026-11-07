<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SellerApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'store_name' => ['required', 'string', 'min:3', 'max:100'],
            'tax_number' => ['required', 'string', 'min:10', 'max:11'],
            'iban' => ['required', 'string', 'min:15', 'max:34'],
        ];
    }

    public function messages(): array
    {
        return [
            'store_name.required' => 'Mağaza adı zorunludur.',
            'store_name.min' => 'Mağaza adı en az 3 karakter olmalıdır.',
            'tax_number.required' => 'Vergi kimlik numarası (veya TCKN) zorunludur.',
            'tax_number.min' => 'Vergi numarası en az 10 karakter olmalıdır.',
            'iban.required' => 'IBAN numarası zorunludur.',
            'iban.min' => 'Lütfen geçerli bir IBAN giriniz.',
        ];
    }
}
