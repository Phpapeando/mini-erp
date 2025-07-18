<?php

use App\Http\Controllers\CarrinhoController;
use App\Http\Controllers\EnderecoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProdutoController;
use Illuminate\Support\Facades\Route;

Route::get('', fn() => redirect()->route('produtos.index'));

Route::get('/produtos', [ProdutoController::class, 'index'])->name('produtos.index');
Route::post('/produtos', [ProdutoController::class, 'store'])->name('produtos.store');
Route::get('/produtos/{produto}/edit', [ProdutoController::class, 'edit'])->name('produtos.edit');
Route::put('/produtos/{produto}', [ProdutoController::class, 'update'])->name('produtos.update');

Route::post('/carrinho/adicionar', [CarrinhoController::class, 'adicionar'])->name('carrinho.adicionar');
Route::get('/carrinho', [CarrinhoController::class, 'exibir'])->name('carrinho.exibir');
Route::post('/carrinho/limpar', [CarrinhoController::class, 'limpar'])->name('carrinho.limpar');

// Rotas para endereço
Route::post('/endereco/buscar-cep', [EnderecoController::class, 'buscarCep'])->name('endereco.buscar-cep');
Route::post('/endereco/salvar', [EnderecoController::class, 'salvarEndereco'])->name('endereco.salvar');
Route::delete('/endereco/remover', [EnderecoController::class, 'removerEndereco'])->name('endereco.remover');

// Rotas para pedidos
Route::post('/pedido/finalizar', [PedidoController::class, 'finalizar'])->name('pedido.finalizar');
Route::get('/pedido/historico', [PedidoController::class, 'historico'])->name('pedido.historico');

// Rota de teste (remover em produção)
if (app()->environment('local')) {
    Route::get('/pedido/teste/adicionar', [PedidoController::class, 'adicionarPedidoTeste'])->name('pedido.teste.adicionar');
    Route::get('/pedido/teste/limpar', [PedidoController::class, 'limparHistoricoTeste'])->name('pedido.teste.limpar');
}
