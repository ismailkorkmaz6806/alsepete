<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        $category = $this->route('category');

        return $this->user() && $this->user()->can('update', $category);
    }

    public function rules(): array
    {
        $categoryId = $this->route('category') ? $this->route('category')->id : null;

        return [
            'name' => ['required', 'string', 'max:255'],
            'parent_id' => ['nullable', 'exists:categories,id', 'not_in:'.$categoryId],
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
            'parent_id.not_in' => 'Bir kategori kendisinin alt kategorisi olamaz.',
            'commission_rate.required' => 'Komisyon oranı zorunludur.',
            'commission_rate.numeric' => 'Komisyon oranı sayısal bir değer olmalıdır.',
            'sort.integer' => 'Sıralama bir tam sayı olmalıdır.',
        ];
    }
}
