<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProdutoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome' => 'required|string|max:255',
            'preco' => 'required|string',
            'variacoes' => 'required|array|min:1',
            'variacoes.*.variacao' => 'required|string|max:255',
            'variacoes.*.quantidade' => 'required|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'O nome do produto é obrigatório.',
            'nome.max' => 'O nome não pode ter mais que 255 caracteres.',
            'preco.required' => 'O preço é obrigatório.',
            'variacoes.required' => 'É necessário adicionar pelo menos uma variação.',
            'variacoes.min' => 'É necessário adicionar pelo menos uma variação.',
            'variacoes.*.variacao.required' => 'A descrição da variação é obrigatória.',
            'variacoes.*.variacao.max' => 'A descrição da variação não pode ter mais que 255 caracteres.',
            'variacoes.*.quantidade.required' => 'A quantidade em estoque é obrigatória.',
            'variacoes.*.quantidade.integer' => 'A quantidade deve ser um número inteiro.',
            'variacoes.*.quantidade.min' => 'A quantidade não pode ser negativa.',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Converter preço brasileiro (vírgula) para formato de banco (ponto)
        if ($this->has('preco')) {
            $preco = str_replace(',', '.', $this->preco);
            
            if (!is_numeric($preco) || $preco < 0) {
                $this->merge(['preco_convertido' => null]);
            } else {
                $this->merge(['preco_convertido' => $preco]);
            }
        }
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($this->preco_convertido === null) {
                $validator->errors()->add('preco', 'O preço deve ser um valor válido (ex: 19,90)');
            }
        });
    }

    public function getPrecoConvertido(): float
    {
        return (float) $this->preco_convertido;
    }
}
