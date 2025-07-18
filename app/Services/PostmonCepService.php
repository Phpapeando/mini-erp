<?php

namespace App\Services;

use App\Contracts\CepServiceInterface;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PostmonCepService implements CepServiceInterface
{
    private const BASE_URL = 'https://api.postmon.com.br/v1/cep';
    private const TIMEOUT = 10; // segundos

    public function buscarCep(string $cep): ?array
    {
        try {
            $cepLimpo = $this->limparCep($cep);

            if (!$this->validarCep($cepLimpo)) {
                Log::warning('CEP inválido no Postmon', ['cep' => $cep]);
                return null;
            }

            Log::info('Buscando CEP no Postmon', ['cep' => $cepLimpo]);

            $response = Http::timeout(self::TIMEOUT)
                ->retry(2, 1000) // Tenta 2 vezes com 1 segundo de intervalo
                ->get(self::BASE_URL . "/{$cepLimpo}");

            if (!$response->successful()) {
                $statusCode = $response->status();
                $message = "API Postmon indisponível (HTTP {$statusCode})";
                
                if ($statusCode === 503) {
                    $message .= " - Serviço temporariamente indisponível";
                } elseif ($statusCode === 404) {
                    $message .= " - CEP não encontrado";
                } elseif ($statusCode >= 500) {
                    $message .= " - Erro interno do servidor Postmon";
                }
                
                Log::warning($message, [
                    'cep' => $cepLimpo,
                    'status' => $statusCode,
                    'body' => $response->body()
                ]);
                return null;
            }

            $data = $response->json();

            // Verifica se o Postmon retornou erro ou dados vazios
            if (isset($data['error']) || empty($data['logradouro'])) {
                Log::warning('CEP não encontrado no Postmon', ['cep' => $cepLimpo, 'response' => $data]);
                return null;
            }

            Log::info('CEP encontrado no Postmon', ['cep' => $cepLimpo]);

            return $this->formatarResposta($data);

        } catch (Exception $e) {
            Log::error('Erro ao buscar CEP no Postmon', [
                'cep' => $cep,
                'error' => $e->getMessage(),
                'type' => get_class($e)
            ]);
            return null;
        }
    }

    public function validarCep(string $cep): bool
    {
        $cepLimpo = $this->limparCep($cep);
        return preg_match('/^\d{8}$/', $cepLimpo);
    }

    private function limparCep(string $cep): string
    {
        return preg_replace('/\D/', '', $cep);
    }

    private function formatarResposta(array $data): array
    {
        return [
            'cep' => $data['cep'] ?? '',
            'logradouro' => $data['logradouro'] ?? '',
            'complemento' => $data['complemento'] ?? '',
            'bairro' => $data['distrito'] ?? $data['bairro'] ?? '',
            'localidade' => $data['cidade'] ?? '',
            'uf' => $data['estado'] ?? '',
            'ibge' => $data['cidade_info']['codigo_ibge'] ?? '',
            'gia' => '',
            'ddd' => $data['cidade_info']['ddd'] ?? '',
            'siafi' => '',
        ];
    }
}
