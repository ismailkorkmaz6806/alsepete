<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->can('create', Category::class);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'parent_id' => ['nullable', 'exists:categories,id'],
            'commission_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'sort' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Kategori adı zorunludur.',
            'name.max' => 'Kategori adı en fazla 255 karakter olabilir.',
            'parent_id.exists' => 'Seçilen üst kategori geçerli değil.',
            'commission_rate.required' => 'Komisyon oranı zorunludur.',
            'commission_rate.numeric' => 'Komisyon oranı sayısal bir değer olmalıdır.',
            'commission_rate.min' => 'Komisyon oranı 0\'dan küçük olamaz.',
            'commission_rate.max' => 'Komisyon oranı 100\'den büyük olamaz.',
            'sort.integer' => 'Sıralama bir tam sayı olmalıdır.',
        ];
    }
}
