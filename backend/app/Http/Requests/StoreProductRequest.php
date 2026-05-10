<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:255', Rule::unique('products', 'name')],
            'description' => ['nullable', 'string', 'max:5000'],
            'price' => ['required', 'numeric', 'gt:0', 'decimal:0,2'],
            'stock' => ['required', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'Já existe um produto com esse nome.',
            'price.gt' => 'O preço deve ser maior que zero.',
            'stock.min' => 'O estoque não pode ser negativo.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nome',
            'description' => 'descrição',
            'price' => 'preço',
            'stock' => 'estoque',
        ];
    }
}
