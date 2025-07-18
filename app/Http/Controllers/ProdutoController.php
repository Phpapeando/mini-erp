<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProdutoRequest;
use App\Models\Estoque;
use App\Models\Produto;
use App\Services\FreteService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProdutoController extends Controller
{
    public function __construct(private FreteService $freteService)
    {}

    public function index()
    {
        $produtos = Produto::with('estoques')->get();
        
        $carrinho = session('carrinho', []);
        $subtotal = 0;

        foreach ($carrinho as $item) {
            $subtotal += $item['preco'] * $item['quantidade'];
        }

        $frete = $this->freteService->calculaFrete($subtotal);

        $endereco = session('endereco_entrega');
        
        return view('produtos.index', compact('produtos', 'carrinho', 'subtotal', 'frete', 'endereco'));
    }

    public function store(ProdutoRequest $request)
    {
        DB::beginTransaction();

        try {
            $produto = Produto::create([
                'nome' => $request->nome,
                'preco' => $request->getPrecoConvertido(),
            ]);

            foreach ($request->variacoes as $item) {
                Estoque::create([
                    'produto_id' => $produto->id,
                    'variacao' => $item['variacao'],
                    'quantidade' => $item['quantidade'],
                ]);
            }

            DB::commit();
            
            Log::info('Produto cadastrado', [
                'produto_id' => $produto->id,
                'nome' => $produto->nome,
                'preco' => $produto->preco,
                'variacoes_count' => count($request->variacoes)
            ]);
            
            return redirect()->route('produtos.index')->with('success', 'Produto cadastrado com sucesso!');
            
        } catch (Exception $e) {
            DB::rollBack();
            
            Log::error('Erro ao cadastrar produto', [
                'error' => $e->getMessage(),
                'dados' => $request->validated()
            ]);
            
            return back()->withErrors(['error' => 'Erro ao cadastrar produto. Tente novamente.'])->withInput();
        }
    }

    public function edit(Produto $produto)
    {
        $produtos = Produto::with('estoques')->get();
        
        // Informações do carrinho
        $carrinho = session('carrinho', []);
        $subtotal = 0;

        foreach ($carrinho as $item) {
            $subtotal += $item['preco'] * $item['quantidade'];
        }

        $frete = $this->freteService->calculaFrete($subtotal);

        $endereco = session('endereco_entrega');

        return view('produtos.index', [
            'produtoEditando' => $produto,
            'produtos' => $produtos,
            'carrinho' => $carrinho,
            'subtotal' => $subtotal,
            'frete' => $frete,
            'endereco' => $endereco,
        ]);
    }

    public function update(ProdutoRequest $request, Produto $produto)
    {
        DB::beginTransaction();
        
        try {
            $produto->update([
                'nome' => $request->nome,
                'preco' => $request->getPrecoConvertido(),
            ]);

            $produto->estoques()->delete();

            foreach ($request->variacoes as $item) {
                $produto->estoques()->create([
                    'variacao' => $item['variacao'],
                    'quantidade' => $item['quantidade'],
                ]);
            }

            DB::commit();
            
            Log::info('Produto atualizado', [
                'produto_id' => $produto->id,
                'nome' => $produto->nome,
                'preco' => $produto->preco,
                'variacoes_count' => count($request->variacoes)
            ]);

            return redirect()->route('produtos.index')->with('success', 'Produto atualizado com sucesso!');
            
        } catch (Exception $e) {
            DB::rollBack();
            
            Log::error('Erro ao atualizar produto', [
                'produto_id' => $produto->id,
                'error' => $e->getMessage(),
                'dados' => $request->validated()
            ]);
            
            return back()->withErrors(['error' => 'Erro ao atualizar produto. Tente novamente.'])->withInput();
        }
    }

    public function destroy(Produto $produto)
    {
        $produto->delete();
        return redirect()->route('produtos.index')->with('success', 'Product deleted successfully.');
    }
}
