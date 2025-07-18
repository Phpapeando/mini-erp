<?php

namespace App\Services;

use App\Models\Estoque;
use App\Models\Produto;
use App\Exceptions\EstoqueInsuficienteException;
use Illuminate\Support\Facades\Log;

class CarrinhoService
{
    private FreteService $freteService;

    public function __construct(FreteService $freteService)
    {
        $this->freteService = $freteService;
    }

    /**
     * Adiciona um produto ao carrinho
     */
    public function adicionarProduto(int $estoqueId, int $quantidade): void
    {
        $estoque = Estoque::with('produto')->findOrFail($estoqueId);
        
        // Verificar estoque disponível
        if ($estoque->quantidade < $quantidade) {
            throw new EstoqueInsuficienteException(
                $estoque->produto->nome,
                $estoque->variacao,
                $estoque->quantidade
            );
        }

        $carrinho = session('carrinho', []);

        if (isset($carrinho[$estoque->id])) {
            $novaQuantidade = $carrinho[$estoque->id]['quantidade'] + $quantidade;

            // Verificar se a nova quantidade total não excede o estoque
            if ($estoque->quantidade < $novaQuantidade) {
                throw new EstoqueInsuficienteException(
                    $estoque->produto->nome,
                    $estoque->variacao,
                    $estoque->quantidade
                );
            }

            $carrinho[$estoque->id]['quantidade'] = $novaQuantidade;
        } else {
            $carrinho[$estoque->id] = [
                'produto_id' => $estoque->produto->id,
                'nome' => $estoque->produto->nome,
                'variacao' => $estoque->variacao,
                'preco' => $estoque->produto->preco,
                'quantidade' => $quantidade,
            ];
        }

        session(['carrinho' => $carrinho]);

        Log::info('Produto adicionado ao carrinho', [
            'produto' => $estoque->produto->nome,
            'variacao' => $estoque->variacao,
            'quantidade' => $quantidade,
            'total_itens_carrinho' => count($carrinho)
        ]);
    }

    /**
     * Obtém os dados do carrinho para exibição
     */
    public function obterDadosCarrinho(): array
    {
        $carrinho = session('carrinho', []);
        $produtos = Produto::with('estoques')->get();
        $endereco = session('endereco_entrega');

        $subtotal = $this->calcularSubtotal($carrinho);
        $frete = $this->freteService->calculaFrete($subtotal);

        return [
            'carrinho' => $carrinho,
            'produtos' => $produtos,
            'endereco' => $endereco,
            'subtotal' => $subtotal,
            'frete' => $frete,
            'total' => $subtotal + $frete
        ];
    }

    /**
     * Calcula o subtotal do carrinho
     */
    private function calcularSubtotal(array $carrinho): float
    {
        $subtotal = 0;
        foreach ($carrinho as $item) {
            $subtotal += $item['preco'] * $item['quantidade'];
        }
        return $subtotal;
    }

    /**
     * Limpa o carrinho
     */
    public function limparCarrinho(): void
    {
        $carrinhoAnterior = session('carrinho', []);
        
        session()->forget('carrinho');
        
        Log::info('Carrinho limpo', [
            'itens_removidos' => count($carrinhoAnterior)
        ]);
    }

    /**
     * Verifica se o carrinho está vazio
     */
    public function carrinhoVazio(): bool
    {
        $carrinho = session('carrinho', []);
        return empty($carrinho);
    }

    /**
     * Obtém o número total de itens no carrinho
     */
    public function contarItens(): int
    {
        $carrinho = session('carrinho', []);
        $total = 0;
        foreach ($carrinho as $item) {
            $total += $item['quantidade'];
        }
        return $total;
    }

    /**
     * Remove um item específico do carrinho
     */
    public function removerItem(int $estoqueId): bool
    {
        $carrinho = session('carrinho', []);
        
        if (isset($carrinho[$estoqueId])) {
            $itemRemovido = $carrinho[$estoqueId];
            unset($carrinho[$estoqueId]);
            session(['carrinho' => $carrinho]);
            
            Log::info('Item removido do carrinho', [
                'produto' => $itemRemovido['nome'],
                'variacao' => $itemRemovido['variacao'],
                'quantidade' => $itemRemovido['quantidade']
            ]);
            
            return true;
        }
        
        return false;
    }

    /**
     * Atualiza a quantidade de um item no carrinho
     */
    public function atualizarQuantidade(int $estoqueId, int $novaQuantidade): void
    {
        if ($novaQuantidade <= 0) {
            $this->removerItem($estoqueId);
            return;
        }

        $estoque = Estoque::with('produto')->findOrFail($estoqueId);
        
        if ($estoque->quantidade < $novaQuantidade) {
            throw new EstoqueInsuficienteException(
                $estoque->produto->nome,
                $estoque->variacao,
                $estoque->quantidade
            );
        }

        $carrinho = session('carrinho', []);
        
        if (isset($carrinho[$estoqueId])) {
            $carrinho[$estoqueId]['quantidade'] = $novaQuantidade;
            session(['carrinho' => $carrinho]);
            
            Log::info('Quantidade atualizada no carrinho', [
                'produto' => $estoque->produto->nome,
                'variacao' => $estoque->variacao,
                'nova_quantidade' => $novaQuantidade
            ]);
        }
    }
}
