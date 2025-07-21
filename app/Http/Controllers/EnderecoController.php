<?php

namespace App\Http\Controllers;

use App\Contracts\CepServiceInterface;
use App\Http\Requests\BuscarCepRequest;
use App\Http\Requests\SalvarEnderecoRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class EnderecoController extends Controller
{
    public function __construct(private CepServiceInterface $cepService)
    {}

    public function buscarCep(BuscarCepRequest $request): JsonResponse
    {
        $cep = $request->cep;

        if (!$this->cepService->validarCep($cep)) {
            Log::warning('CEP inválido informado', ['cep' => $cep]);
            return response()->json([
                'success' => false,
                'message' => 'CEP inválido. O CEP deve conter 8 dígitos.'
            ], 400);
        }

        $endereco = $this->cepService->buscarCep($cep);

        if (!$endereco) {
            Log::warning('CEP não encontrado', ['cep' => $cep]);
            return response()->json([
                'success' => false,
                'message' => 'CEP não encontrado ou serviço indisponível.'
            ], 404);
        }

        Log::info('CEP consultado com sucesso', ['cep' => $cep]);

        return response()->json([
            'success' => true,
            'data' => $endereco
        ]);
    }

    public function salvarEndereco(SalvarEnderecoRequest $request): JsonResponse
    {
        $endereco = [
            'cep' => $request->cep,
            'logradouro' => $request->logradouro,
            'numero' => $request->numero,
            'complemento' => $request->complemento,
            'bairro' => $request->bairro,
            'localidade' => $request->localidade,
            'uf' => $request->uf,
        ];

        session(['endereco_entrega' => $endereco]);

        return response()->json([
            'success' => true,
            'message' => 'Endereço salvo com sucesso!'
        ]);
    }

    public function removerEndereco(): JsonResponse
    {
        session()->forget('endereco_entrega');

        return response()->json([
            'success' => true,
            'message' => 'Endereço removido com sucesso!'
        ]);
    }
}
