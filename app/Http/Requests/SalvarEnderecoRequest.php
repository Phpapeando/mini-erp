<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SalvarEnderecoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cep' => 'required|string|max:10',
            'logradouro' => 'required|string|max:255',
            'numero' => 'required|string|max:10',
            'complemento' => 'nullable|string|max:255',
            'bairro' => 'required|string|max:255',
            'localidade' => 'required|string|max:255',
            'uf' => 'required|string|size:2',
        ];
    }

    public function messages(): array
    {
        return [
            'cep.required' => 'O CEP é obrigatório.',
            'cep.string' => 'O CEP deve ser uma string.',
            'cep.max' => 'O CEP não pode ter mais que 10 caracteres.',
            'logradouro.required' => 'O logradouro é obrigatório.',
            'logradouro.string' => 'O logradouro deve ser uma string.',
            'logradouro.max' => 'O logradouro não pode ter mais que 255 caracteres.',
            'numero.required' => 'O número é obrigatório.',
            'numero.string' => 'O número deve ser uma string.',
            'numero.max' => 'O número não pode ter mais que 20 caracteres.',
            'complemento.string' => 'O complemento deve ser uma string.',
            'complemento.max' => 'O complemento não pode ter mais que 255 caracteres.',
            'bairro.required' => 'O bairro é obrigatório.',
            'bairro.string' => 'O bairro deve ser uma string.',
            'bairro.max' => 'O bairro não pode ter mais que 100 caracteres.',
            'localidade.required' => 'A cidade é obrigatória.',
            'localidade.string' => 'A cidade deve ser uma string.',
            'localidade.max' => 'A cidade não pode ter mais que 100 caracteres.',
            'uf.required' => 'O estado é obrigatório.',
            'uf.string' => 'O estado deve ser uma string.',
            'uf.size' => 'O estado deve ter exatamente 2 caracteres.',
        ];
    }
}
