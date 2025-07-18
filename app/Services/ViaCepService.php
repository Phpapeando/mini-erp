<?php

namespace App\Services;

use App\Contracts\CepServiceInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ViaCepService implements CepServiceInterface
{
    private const BASE_URL = 'https://viacep.com.br/ws';
    private const TIMEOUT = 10; // segundos

    /**
     * Busca informações de endereço por CEP usando ViaCEP
     *
     * @param string $cep
     * @return array|null
     */
    public function buscarCep(string $cep): ?array
    {
        try {
            // Remove formatação do CEP
            $cepLimpo = $this->limparCep($cep);

            if (!$this->validarCep($cepLimpo)) {
                return null;
            }

            $response = Http::timeout(self::TIMEOUT)
                ->get(self::BASE_URL . "/{$cepLimpo}/json/");

            if (!$response->successful()) {
                Log::warning('Erro na requisição ViaCEP', [
                    'cep' => $cepLimpo,
                    'status' => $response->status()
                ]);
                return null;
            }

            $data = $response->json();

            // ViaCEP retorna erro quando CEP não é encontrado
            if (isset($data['erro']) && $data['erro']) {
                return null;
            }

            return $this->formatarResposta($data);

        } catch (\Exception $e) {
            Log::error('Erro ao buscar CEP no ViaCEP', [
                'cep' => $cep,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Valida se o CEP está no formato correto
     *
     * @param string $cep
     * @return bool
     */
    public function validarCep(string $cep): bool
    {
        $cepLimpo = $this->limparCep($cep);
        return preg_match('/^\d{8}$/', $cepLimpo);
    }

    /**
     * Remove formatação do CEP (hífens, espaços, etc.)
     *
     * @param string $cep
     * @return string
     */
    private function limparCep(string $cep): string
    {
        return preg_replace('/\D/', '', $cep);
    }

    /**
     * Formata a resposta do ViaCEP para um padrão consistente
     *
     * @param array $data
     * @return array
     */
    private function formatarResposta(array $data): array
    {
        return [
            'cep' => $data['cep'] ?? '',
            'logradouro' => $data['logradouro'] ?? '',
            'complemento' => $data['complemento'] ?? '',
            'bairro' => $data['bairro'] ?? '',
            'localidade' => $data['localidade'] ?? '',
            'uf' => $data['uf'] ?? '',
            'ibge' => $data['ibge'] ?? '',
            'gia' => $data['gia'] ?? '',
            'ddd' => $data['ddd'] ?? '',
            'siafi' => $data['siafi'] ?? '',
        ];
    }
}
