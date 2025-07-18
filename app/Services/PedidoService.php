<?php

namespace App\Services;

use App\Mail\PedidoFinalizadoMail;
use App\Models\Estoque;
use App\Exceptions\CarrinhoVazioException;
use App\Exceptions\EnderecoNaoInformadoException;
use App\Exceptions\EstoqueInsuficienteException;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class PedidoService
{
    /**
     * Finaliza um pedido completo
     */
    public function finalizarPedido(string $email, string $nome): array
    {
        $this->validarDadosSessao();
        
        $carrinho = session('carrinho', []);
        $endereco = session('endereco_entrega');
        
        $valores = $this->calcularValores($carrinho);
        $numeroPedido = $this->gerarNumeroPedido();
        
        $this->atualizarEstoque($carrinho);
        
        $this->enviarEmailConfirmacao($email, $carrinho, $endereco, $valores, $numeroPedido);
        
        $this->salvarNoHistorico($numeroPedido, $email, $nome, $carrinho, $endereco, $valores);
        
        $this->limparSessao();
        
        return [
            'numero' => $numeroPedido,
            'total' => $valores['total']
        ];
    }
    
    private function validarDadosSessao(): void
    {
        $carrinho = session('carrinho', []);
        if (empty($carrinho)) {
            throw new CarrinhoVazioException();
        }
        
        $endereco = session('endereco_entrega');
        if (!$endereco) {
            throw new EnderecoNaoInformadoException();
        }
    }
    
    private function calcularValores(array $carrinho): array
    {
        $subtotal = 0;
        foreach ($carrinho as $item) {
            $subtotal += $item['preco'] * $item['quantidade'];
        }
        
        // Regras de frete
        if ($subtotal > 200) {
            $frete = 0;
        } elseif ($subtotal >= 52 && $subtotal <= 166.59) {
            $frete = 15;
        } else {
            $frete = 20;
        }
        
        return [
            'subtotal' => $subtotal,
            'frete' => $frete,
            'total' => $subtotal + $frete
        ];
    }
    
    /**
     * Atualiza o estoque dos produtos vendidos
     */
    private function atualizarEstoque(array $carrinho): void
    {
        foreach ($carrinho as $estoqueId => $item) {
            $estoque = Estoque::find($estoqueId);
            if (!$estoque) {
                continue;
            }
            
            // Verificar estoque disponível
            if ($estoque->quantidade < $item['quantidade']) {
                throw new EstoqueInsuficienteException(
                    $item['nome'],
                    $item['variacao'],
                    $estoque->quantidade
                );
            }
            
            // Reduzir estoque
            $estoque->quantidade -= $item['quantidade'];
            $estoque->save();
            
            Log::info('Estoque atualizado', [
                'produto' => $item['nome'],
                'variacao' => $item['variacao'],
                'quantidade_vendida' => $item['quantidade'],
                'estoque_restante' => $estoque->quantidade
            ]);
        }
    }
    
    /**
     * Envia e-mail de confirmação do pedido
     */
    private function enviarEmailConfirmacao(
        string $email,
        array $carrinho,
        array $endereco,
        array $valores,
        string $numeroPedido
    ): void {
        // Converter carrinho para formato do e-mail
        $carrinhoFormatado = [];
        foreach ($carrinho as $item) {
            $carrinhoFormatado[] = [
                'nome' => $item['nome'],
                'variacao' => $item['variacao'],
                'preco' => $item['preco'],
                'quantidade' => $item['quantidade']
            ];
        }
        
        Mail::to($email)->send(new PedidoFinalizadoMail(
            $carrinhoFormatado,
            $endereco,
            $valores['subtotal'],
            $valores['frete'],
            $numeroPedido
        ));
    }
    
    /**
     * Salva o pedido no histórico da sessão
     */
    private function salvarNoHistorico(
        string $numeroPedido,
        string $email,
        string $nome,
        array $carrinho,
        array $endereco,
        array $valores
    ): void {
        // Formatar carrinho para histórico
        $carrinhoHistorico = [];
        foreach ($carrinho as $item) {
            $carrinhoHistorico[] = [
                'nome' => $item['nome'],
                'variacao' => $item['variacao'],
                'preco' => $item['preco'],
                'quantidade' => $item['quantidade']
            ];
        }
        
        $dadosPedido = [
            'numero' => $numeroPedido,
            'email' => $email,
            'nome' => $nome,
            'carrinho' => $carrinhoHistorico,
            'endereco' => $endereco,
            'subtotal' => $valores['subtotal'],
            'frete' => $valores['frete'],
            'total' => $valores['total'],
            'data' => now()->format('d/m/Y H:i:s')
        ];
        
        $historico = session('historico_pedidos', []);
        $historico[] = $dadosPedido;
        session(['historico_pedidos' => $historico]);
    }
    
    /**
     * Limpa dados da sessão após finalizar pedido
     */
    private function limparSessao(): void
    {
        session()->forget(['carrinho', 'endereco_entrega']);
    }
    
    /**
     * Gera um número único para o pedido
     */
    private function gerarNumeroPedido(): string
    {
        return 'PED-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
    }
    
    /**
     * Retorna o histórico de pedidos validado
     */
    public function obterHistorico(): array
    {
        $historico = session('historico_pedidos', []);
        
        if (!is_array($historico)) {
            return [];
        }
        
        $historicoValidado = [];
        foreach ($historico as $pedido) {
            if (is_array($pedido) && isset($pedido['numero'], $pedido['data'])) {
                // Garantir estrutura dos dados
                $pedido['carrinho'] = $pedido['carrinho'] ?? [];
                $pedido['endereco'] = $pedido['endereco'] ?? $this->getEnderecoDefault();
                $pedido['subtotal'] = floatval($pedido['subtotal'] ?? 0);
                $pedido['frete'] = floatval($pedido['frete'] ?? 0);
                $pedido['total'] = floatval($pedido['total'] ?? 0);
                
                $historicoValidado[] = $pedido;
            }
        }
        
        return array_reverse($historicoValidado); // Mais recentes primeiro
    }
    
    /**
     * Retorna endereço padrão para dados inconsistentes
     */
    private function getEnderecoDefault(): array
    {
        return [
            'logradouro' => 'N/A',
            'numero' => 'N/A',
            'bairro' => 'N/A',
            'localidade' => 'N/A',
            'uf' => 'N/A'
        ];
    }
}
