<?php

namespace App\Services;

use App\Contracts\CepServiceInterface;
use Illuminate\Support\Facades\Log;

class HybridCepService implements CepServiceInterface
{
    private CepServiceInterface $primaryService;
    private CepServiceInterface $fallbackService;

    public function __construct()
    {
        $this->primaryService = new ViaCepService();
        $this->fallbackService = new PostmonCepService();
    }

    public function buscarCep(string $cep): ?array
    {
        Log::info('Iniciando busca híbrida de CEP', ['cep' => $cep]);

        $resultado = $this->primaryService->buscarCep($cep);
        
        if ($resultado !== null) {
            Log::info('CEP encontrado no serviço principal (ViaCEP)', ['cep' => $cep]);
            return $resultado;
        }

        Log::info('CEP não encontrado no ViaCEP, tentando Postmon', ['cep' => $cep]);

        $resultado = $this->fallbackService->buscarCep($cep);
        
        if ($resultado !== null) {
            Log::info('CEP encontrado no serviço de fallback (Postmon)', ['cep' => $cep]);
            return $resultado;
        }

        Log::warning('CEP não encontrado em nenhum serviço', ['cep' => $cep]);
        return null;
    }

    public function validarCep(string $cep): bool
    {
        return $this->primaryService->validarCep($cep);
    }
}
