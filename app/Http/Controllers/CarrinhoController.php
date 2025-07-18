<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdicionarCarrinhoRequest;
use App\Http\Requests\AtualizarCarrinhoRequest;
use App\Http\Requests\RemoverCarrinhoRequest;
use App\Services\CarrinhoService;
use App\Exceptions\EstoqueInsuficienteException;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class CarrinhoController extends Controller
{
    private CarrinhoService $carrinhoService;

    public function __construct(CarrinhoService $carrinhoService)
    {
        $this->carrinhoService = $carrinhoService;
    }
    
    /**
     * Adiciona um produto ao carrinho
     */
    public function adicionar(AdicionarCarrinhoRequest $request): RedirectResponse
    {
        try {
            $this->carrinhoService->adicionarProduto(
                $request->estoque_id,
                $request->quantidade
            );

            return redirect()->route('carrinho.exibir')
                ->with('success', 'Produto adicionado ao carrinho com sucesso!');

        } catch (EstoqueInsuficienteException $e) {
            return back()->withErrors(['error' => $e->getMessage()]);

        } catch (\Exception $e) {
            Log::error('Erro ao adicionar produto ao carrinho', [
                'error' => $e->getMessage(),
                'estoque_id' => $request->estoque_id,
                'quantidade' => $request->quantidade
            ]);

            return back()->withErrors(['error' => 'Erro interno. Tente novamente.']);
        }
    }

    /**
     * Exibe o carrinho de compras
     */
    public function exibir(): View
    {
        $dados = $this->carrinhoService->obterDadosCarrinho();

        return view('produtos.index', $dados);
    }

    /**
     * Limpa todo o carrinho
     */
    public function limpar(): RedirectResponse
    {
        try {
            $this->carrinhoService->limparCarrinho();
            
            return redirect()->route('produtos.index')
                ->with('success', 'Carrinho limpo com sucesso!');

        } catch (\Exception $e) {
            Log::error('Erro ao limpar carrinho', [
                'error' => $e->getMessage()
            ]);

            return back()->withErrors(['error' => 'Erro ao limpar carrinho.']);
        }
    }

    /**
     * Remove um item específico do carrinho
     */
    public function remover(RemoverCarrinhoRequest $request): RedirectResponse
    {
        try {
            $removido = $this->carrinhoService->removerItem($request->estoque_id);
            
            if ($removido) {
                return back()->with('success', 'Produto removido do carrinho!');
            } else {
                return back()->withErrors(['error' => 'Produto não encontrado no carrinho.']);
            }

        } catch (\Exception $e) {
            Log::error('Erro ao remover item do carrinho', [
                'error' => $e->getMessage(),
                'estoque_id' => $request->estoque_id
            ]);

            return back()->withErrors(['error' => 'Erro ao remover produto.']);
        }
    }

    /**
     * Atualiza a quantidade de um item no carrinho
     */
    public function atualizar(AtualizarCarrinhoRequest $request): RedirectResponse
    {
        try {
            $this->carrinhoService->atualizarQuantidade(
                $request->estoque_id,
                $request->quantidade
            );
            
            return back()->with('success', 'Quantidade atualizada!');

        } catch (EstoqueInsuficienteException $e) {
            return back()->withErrors(['error' => $e->getMessage()]);

        } catch (\Exception $e) {
            Log::error('Erro ao atualizar quantidade no carrinho', [
                'error' => $e->getMessage(),
                'estoque_id' => $request->estoque_id,
                'quantidade' => $request->quantidade
            ]);

            return back()->withErrors(['error' => 'Erro ao atualizar quantidade.']);
        }
    }
}
