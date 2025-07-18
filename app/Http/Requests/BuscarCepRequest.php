<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BuscarCepRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cep' => 'required|string|min:8|max:10',
        ];
    }

    /**
     * Prepara os dados para validação removendo formatação do CEP
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'cep' => preg_replace('/\D/', '', $this->cep ?? '')
        ]);
    }

    public function messages(): array
    {
        return [
            'cep.required' => 'O CEP é obrigatório.',
            'cep.string' => 'O CEP deve ser uma string.',
            'cep.min' => 'O CEP deve ter pelo menos 8 caracteres.',
            'cep.max' => 'O CEP deve ter no máximo 10 caracteres.',
        ];
    }
}
