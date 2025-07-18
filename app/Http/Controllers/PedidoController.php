<?php

namespace App\Http\Controllers;

use App\Http\Requests\FinalizarPedidoRequest;
use App\Services\PedidoService;
use App\Exceptions\CarrinhoVazioException;
use App\Exceptions\EnderecoNaoInformadoException;
use App\Exceptions\EstoqueInsuficienteException;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class PedidoController extends Controller
{
    public function __construct(
        private PedidoService $pedidoService,
    )
    {}

    public function finalizar(FinalizarPedidoRequest $request): JsonResponse
    {
        try {
            $resultado = $this->pedidoService->finalizarPedido(
                $request->email,
                $request->nome
            );

            return response()->json([
                'success' => true,
                'message' => 'Pedido finalizado com sucesso! E-mail de confirmação enviado.',
                'pedido' => $resultado
            ]);

        } catch (CarrinhoVazioException | EnderecoNaoInformadoException | EstoqueInsuficienteException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);

        } catch (Exception $e) {
            Log::error('Erro ao finalizar pedido', [
                'error' => $e->getMessage(),
                'email' => $request->email,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro interno. Tente novamente.'
            ], 500);
        }
    }

    public function historico(): JsonResponse
    {
        try {
            $historico = $this->pedidoService->obterHistorico();
            
            Log::info('Histórico de pedidos solicitado', [
                'total_pedidos' => count($historico),
                'session_id' => session()->getId()
            ]);
            
            return response()->json([
                'success' => true,
                'pedidos' => $historico,
                'total' => count($historico)
            ]);
            
        } catch (Exception $e) {
            Log::error('Erro ao buscar histórico de pedidos', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor',
                'pedidos' => [],
                'total' => 0
            ], 500);
        }
    }

    public function limparHistoricoTeste(): JsonResponse
    {
        if (!app()->environment('local')) {
            abort(404);
        }

        session()->forget('historico_pedidos');

        return response()->json([
            'success' => true,
            'message' => 'Histórico de pedidos limpo com sucesso!'
        ]);
    }
}
