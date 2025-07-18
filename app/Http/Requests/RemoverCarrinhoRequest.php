<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RemoverCarrinhoRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'estoque_id' => 'required|exists:estoques,id',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'estoque_id.required' => 'O produto é obrigatório.',
            'estoque_id.exists' => 'Produto não encontrado.',
        ];
    }
}
