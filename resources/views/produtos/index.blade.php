@php
    $editando = isset($produtoEditando);
@endphp

@extends('layouts.app')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-box"></i> Gerenciamento de Produtos</h1>
        @if(!$editando)
            <button type="button" class="btn btn-primary" data-bs-toggle="collapse" data-bs-target="#formProduto" aria-expanded="false" aria-controls="formProduto">
                <i class="fas fa-plus"></i> Novo Produto
            </button>
        @endif
    </div>

    {{-- Mensagens --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle"></i> <strong>Erro ao salvar produto:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $erro)
                    <li>{{ $erro }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Formulário de Criação/Edição --}}
    <div class="collapse {{ $editando || $errors->any() ? 'show' : '' }}" id="formProduto">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas {{ $editando ? 'fa-edit' : 'fa-plus-circle' }}"></i>
                    {{ $editando ? 'Editar Produto' : 'Cadastrar Novo Produto' }}
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ $editando ? route('produtos.update', $produtoEditando->id) : route('produtos.store') }}" method="POST">
                    @csrf
                    @if($editando)
                        @method('PUT')
                    @endif
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="nome" class="form-label">
                                    <i class="fas fa-tag"></i> Nome do Produto <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="nome" id="nome" class="form-control" required 
                                       placeholder="Ex: Camisa Social"
                                       value="{{ old('nome', $editando ? $produtoEditando->nome : '') }}">
                                <div class="form-text">Digite um nome descritivo para o produto</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="preco" class="form-label">
                                    <i class="fas fa-dollar-sign"></i> Preço <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="text" name="preco" id="preco" class="form-control" required 
                                           placeholder="0,00" inputmode="decimal"
                                           pattern="[0-9]+([,][0-9]{1,2})?"
                                           title="Use vírgula para separar os decimais (ex: 19,90)"
                                           value="{{ old('preco', $editando ? number_format($produtoEditando->preco, 2, ',', '.') : '') }}">
                                </div>
                                <div class="form-text">Use vírgula para separar os decimais (ex: 19,90)</div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-layer-group"></i> Variações e Estoque <span class="text-danger">*</span>
                        </label>
                        <div class="form-text mb-3">
                            Adicione as diferentes variações do produto (tamanho, cor, modelo) e seus respectivos estoques
                        </div>
                        
                        <div id="variacoes-container">
                            @if($editando)
                                @foreach($produtoEditando->estoques as $i => $estoque)
                                    <div class="variacao-item card mb-2">
                                        <div class="card-body py-2">
                                            <div class="row align-items-center">
                                                <div class="col-md-6">
                                                    <input type="text" name="variacoes[{{ $i }}][variacao]" class="form-control" 
                                                           placeholder="Ex: Tamanho M, Cor Azul"
                                                           value="{{ old("variacoes.$i.variacao", $estoque->variacao) }}">
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <input type="number" name="variacoes[{{ $i }}][quantidade]" class="form-control" 
                                                               placeholder="Quantidade" min="0" step="1"
                                                               value="{{ old("variacoes.$i.quantidade", $estoque->quantidade) }}">
                                                        <span class="input-group-text">un</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <button type="button" class="btn btn-outline-danger btn-sm btn-remove-variacao w-100">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                @php $index = $produtoEditando->estoques->count(); @endphp
                            @else
                                <div class="variacao-item card mb-2">
                                    <div class="card-body py-2">
                                        <div class="row align-items-center">
                                            <div class="col-md-6">
                                                <input type="text" name="variacoes[0][variacao]" class="form-control" 
                                                       placeholder="Ex: Tamanho M, Cor Azul">
                                            </div>
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <input type="number" name="variacoes[0][quantidade]" class="form-control" 
                                                           placeholder="Quantidade" min="0" step="1">
                                                    <span class="input-group-text">un</span>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-outline-danger btn-sm btn-remove-variacao w-100">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @php $index = 1; @endphp
                            @endif
                        </div>

                        <button type="button" class="btn btn-outline-secondary btn-sm" id="add-variacao">
                            <i class="fas fa-plus"></i> Adicionar Variação
                        </button>
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        @if($editando)
                            <a href="{{ route('produtos.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                        @endif
                        <button type="submit" class="btn btn-primary">
                            <i class="fas {{ $editando ? 'fa-save' : 'fa-plus' }}"></i>
                            {{ $editando ? 'Atualizar Produto' : 'Salvar Produto' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Listagem de Produtos --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-list"></i> Produtos Cadastrados
            </h5>
        </div>
        <div class="card-body">
            @if($produtos->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th><i class="fas fa-tag"></i> Produto</th>
                                <th><i class="fas fa-dollar-sign"></i> Preço</th>
                                <th><i class="fas fa-layer-group"></i> Variação</th>
                                <th><i class="fas fa-boxes"></i> Estoque</th>
                                <th><i class="fas fa-cogs"></i> Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($produtos as $produto)
                                @foreach($produto->estoques as $estoque)
                                <tr>
                                    <td>
                                        <strong>{{ $produto->nome }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary fs-6">R$ {{ number_format($produto->preco, 2, ',', '.') }}</span>
                                    </td>
                                    <td>{{ $estoque->variacao }}</td>
                                    <td>
                                        @if($estoque->quantidade > 10)
                                            <span class="badge bg-success">{{ $estoque->quantidade }} un</span>
                                        @elseif($estoque->quantidade > 0)
                                            <span class="badge bg-warning">{{ $estoque->quantidade }} un</span>
                                        @else
                                            <span class="badge bg-danger">Sem estoque</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2 align-items-center">
                                            <a href="{{ route('produtos.edit', $produto->id) }}" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i> Editar
                                            </a>
                                            
                                            @if($estoque->quantidade > 0)
                                                <form method="POST" action="{{ route('carrinho.adicionar') }}" class="d-flex gap-1 align-items-center">
                                                    @csrf
                                                    <input type="hidden" name="estoque_id" value="{{ $estoque->id }}">
                                                    <div class="input-group input-group-sm" style="width: 70px;">
                                                        <input type="number" name="quantidade" value="1" min="1" max="{{ $estoque->quantidade }}"
                                                               class="form-control form-control-sm text-center">
                                                    </div>
                                                    <button type="submit" class="btn btn-success btn-sm">
                                                        <i class="fas fa-shopping-cart"></i> Comprar
                                                    </button>
                                                </form>
                                            @else
                                                <span class="badge bg-secondary">Indisponível</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Nenhum produto cadastrado</h5>
                    <p class="text-muted">Clique em "Novo Produto" para começar</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Carrinho --}}
    <h2>Carrinho de Compras</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(isset($carrinho) && count($carrinho) > 0)
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Itens no Carrinho</h5>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Produto</th>
                                <th>Variação</th>
                                <th>Preço Unit.</th>
                                <th>Qtd</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($carrinho as $item)
                            <tr>
                                <td>{{ $item['nome'] }}</td>
                                <td>{{ $item['variacao'] }}</td>
                                <td>R$ {{ number_format($item['preco'], 2, ',', '.') }}</td>
                                <td>{{ $item['quantidade'] }}</td>
                                <td>R$ {{ number_format($item['preco'] * $item['quantidade'], 2, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="row mt-3">
                    <div class="col-md-8">
                        <form method="POST" action="{{ route('carrinho.limpar') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm" 
                                    onclick="return confirm('Deseja realmente limpar o carrinho?')">
                                <i class="fas fa-trash"></i> Limpar Carrinho
                            </button>
                        </form>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <p class="mb-2">Subtotal: <strong>R$ {{ number_format($subtotal, 2, ',', '.') }}</strong></p>
                                <p class="mb-2">Frete: <strong>R$ {{ number_format($frete, 2, ',', '.') }}</strong></p>
                                <hr>
                                <p class="mb-3 fs-5"><strong>Total: R$ {{ number_format($subtotal + $frete, 2, ',', '.') }}</strong></p>
                                
                                @if(isset($endereco))
                                    <button type="button" class="btn btn-success btn-lg w-100" onclick="mostrarModalFinalizarPedido()">
                                        <i class="fas fa-check-circle"></i> Finalizar Pedido
                                    </button>
                                @else
                                    <div class="alert alert-warning mb-0">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <small>Adicione um endereço de entrega para finalizar o pedido</small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-info">
            <i class="fas fa-shopping-cart"></i> Carrinho vazio. Adicione produtos para começar sua compra!
        </div>
    @endif

    <hr>

    {{-- Endereço de Entrega --}}
    <h2>Endereço de Entrega</h2>

    @if(isset($endereco))
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="fas fa-map-marker-alt"></i> Endereço Cadastrado
                </h5>
                <div class="row">
                    <div class="col-md-8">
                        <p class="mb-1"><strong>{{ $endereco['logradouro'] }}, {{ $endereco['numero'] }}</strong></p>
                        @if($endereco['complemento'])
                            <p class="mb-1">{{ $endereco['complemento'] }}</p>
                        @endif
                        <p class="mb-1">{{ $endereco['bairro'] }} - {{ $endereco['localidade'] }}/{{ $endereco['uf'] }}</p>
                        <p class="mb-0">CEP: {{ $endereco['cep'] }}</p>
                    </div>
                    <div class="col-md-4 text-end">
                        <button type="button" class="btn btn-danger btn-sm" onclick="removerEndereco()">
                            <i class="fas fa-trash"></i> Remover
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="fas fa-search"></i> Buscar CEP
                </h5>
                
                <form id="cepForm" class="mb-3">
                    <div class="row">
                        <div class="col-md-4">
                            <input type="text" id="cep" class="form-control" placeholder="Digite o CEP (ex: 01310-100)" maxlength="10">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-info" onclick="buscarCEP()">
                                <i class="fas fa-search"></i> Buscar
                            </button>
                        </div>
                    </div>
                </form>

                <div id="endereco-form" style="display: none;">
                    <hr>
                    <h6>Complete seu endereço:</h6>
                    <form id="enderecoForm">
                        <input type="hidden" id="endereco-cep">
                        <div class="row">
                            <div class="col-md-8">
                                <label for="logradouro" class="form-label">Logradouro</label>
                                <input type="text" id="logradouro" class="form-control" required readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="numero" class="form-label">Número *</label>
                                <input type="text" id="numero" class="form-control" required>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label for="complemento" class="form-label">Complemento</label>
                                <input type="text" id="complemento" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="bairro" class="form-label">Bairro</label>
                                <input type="text" id="bairro" class="form-control" required readonly>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-8">
                                <label for="localidade" class="form-label">Cidade</label>
                                <input type="text" id="localidade" class="form-control" required readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="uf" class="form-label">UF</label>
                                <input type="text" id="uf" class="form-control" required readonly>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="button" class="btn btn-success" onclick="salvarEndereco()">
                                <i class="fas fa-save"></i> Salvar Endereço
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="cancelarEndereco()">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>

                <div id="cepResultado" class="mt-3"></div>
            </div>
        </div>
    @endif

    <hr>

    {{-- Histórico de Pedidos --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>📋 Histórico de Pedidos</h2>
        <div>
            <button type="button" class="btn btn-outline-primary btn-sm" onclick="carregarHistoricoPedidos()">
                <i class="fas fa-sync-alt"></i> Atualizar
            </button>
        </div>
    </div>
    
    <div id="historico-pedidos" class="mb-4">
        <div class="text-center text-muted">
            <i class="fas fa-spinner fa-spin"></i> Carregando histórico...
        </div>
    </div>

</div>

@endsection
@push('scripts')
<script>
    let index = {{ $index }};

    // Função para mostrar notificações visuais
    function mostrarNotificacao(mensagem, tipo = 'info', tempo = 5000) {
        const container = document.querySelector('.container');
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${tipo} alert-dismissible fade show notification-toast`;
        
        alertDiv.innerHTML = `
            ${mensagem}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        
        container.appendChild(alertDiv);
        
        // Remove automaticamente após o tempo especificado
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.classList.add('fade');
                setTimeout(() => {
                    alertDiv.remove();
                }, 150);
            }
        }, tempo);
    }

    // Função para mostrar confirmação personalizada
    function mostrarConfirmacao(mensagem, callback) {
        const container = document.querySelector('.container');
        const modalDiv = document.createElement('div');
        modalDiv.innerHTML = `
            <div class="modal fade" id="confirmarModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-confirm">
                    <div class="modal-content">
                        <div class="modal-header bg-light">
                            <h5 class="modal-title"><i class="fas fa-question-circle text-warning"></i> Confirmação</h5>
                        </div>
                        <div class="modal-body">
                            <p class="mb-0">${mensagem}</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times"></i> Cancelar
                            </button>
                            <button type="button" class="btn btn-danger" id="confirmarAcao">
                                <i class="fas fa-check"></i> Confirmar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        container.appendChild(modalDiv);
        const modal = new bootstrap.Modal(document.getElementById('confirmarModal'));
        
        document.getElementById('confirmarAcao').addEventListener('click', () => {
            modal.hide();
            callback();
            modalDiv.remove();
        });
        
        document.getElementById('confirmarModal').addEventListener('hidden.bs.modal', () => {
            modalDiv.remove();
        });
        
        modal.show();
    }

    // Função para mostrar modal de finalizar pedido
    function mostrarModalFinalizarPedido() {
        const container = document.querySelector('.container');
        const modalDiv = document.createElement('div');
        modalDiv.innerHTML = `
            <div class="modal fade" id="finalizarPedidoModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title">
                                <i class="fas fa-check-circle"></i> Finalizar Pedido
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                Preencha seus dados para receber a confirmação do pedido por e-mail.
                            </div>
                            
                            <form id="finalizarPedidoForm">
                                <div class="mb-3">
                                    <label for="nome-cliente" class="form-label">Nome Completo *</label>
                                    <input type="text" id="nome-cliente" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email-cliente" class="form-label">E-mail *</label>
                                    <input type="email" id="email-cliente" class="form-control" required>
                                </div>
                                
                                <div class="bg-light p-3 rounded">
                                    <h6><i class="fas fa-shopping-cart"></i> Resumo do Pedido</h6>
                                    <p class="mb-1">Subtotal: <strong>R$ {{ number_format($subtotal ?? 0, 2, ',', '.') }}</strong></p>
                                    <p class="mb-1">Frete: <strong>R$ {{ number_format($frete ?? 0, 2, ',', '.') }}</strong></p>
                                    <hr class="my-2">
                                    <p class="mb-0 fw-bold">Total: <strong>R$ {{ number_format(($subtotal ?? 0) + ($frete ?? 0), 2, ',', '.') }}</strong></p>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times"></i> Cancelar
                            </button>
                            <button type="button" class="btn btn-success" id="confirmarPedido">
                                <i class="fas fa-paper-plane"></i> Confirmar Pedido
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        container.appendChild(modalDiv);
        const modal = new bootstrap.Modal(document.getElementById('finalizarPedidoModal'));
        
        document.getElementById('confirmarPedido').addEventListener('click', () => {
            finalizarPedido();
        });
        
        document.getElementById('finalizarPedidoModal').addEventListener('hidden.bs.modal', () => {
            modalDiv.remove();
        });
        
        modal.show();
    }

    // Função para finalizar pedido
    function finalizarPedido() {
        const nome = document.getElementById('nome-cliente').value.trim();
        const email = document.getElementById('email-cliente').value.trim();
        if (!nome || !email) {
            mostrarNotificacao('<i class="fas fa-exclamation-triangle"></i> Por favor, preencha todos os campos obrigatórios.', 'warning');
            return;
        }

        if (!validateEmail(email)) {
            mostrarNotificacao('<i class="fas fa-exclamation-triangle"></i> Por favor, insira um e-mail válido.', 'warning');
            return;
        }

        const btnConfirmar = document.getElementById('confirmarPedido');
        const textoOriginal = btnConfirmar.innerHTML;
        btnConfirmar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processando...';
        btnConfirmar.disabled = true;

        fetch('{{ route("pedido.finalizar") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                nome: nome,
                email: email
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Fechar modal
                bootstrap.Modal.getInstance(document.getElementById('finalizarPedidoModal')).hide();
                
                // Mostrar mensagem de sucesso
                mostrarNotificacao(
                    '<i class="fas fa-check-circle"></i> ' + data.message + 
                    '<br><small>Pedido: ' + data.pedido.numero + '</small>', 
                    'success', 
                    8000
                );
                
                // Recarregar página após 2 segundos
                setTimeout(() => {
                    location.reload();
                }, 2000);
            } else {
                mostrarNotificacao('<i class="fas fa-times-circle"></i> ' + data.message, 'danger');
                btnConfirmar.innerHTML = textoOriginal;
                btnConfirmar.disabled = false;
            }
        })
        .catch(error => {
            mostrarNotificacao('<i class="fas fa-times-circle"></i> Erro ao finalizar pedido. Tente novamente.', 'danger');
            btnConfirmar.innerHTML = textoOriginal;
            btnConfirmar.disabled = false;
        });
    }

    // Função para validar e-mail
    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    // Função para carregar histórico de pedidos
    function carregarHistoricoPedidos() {
        const historicoDiv = document.getElementById('historico-pedidos');
        const btnAtualizar = document.querySelector('button[onclick="carregarHistoricoPedidos()"]');
        
        // Mostrar loading no botão
        const textoOriginal = btnAtualizar.innerHTML;
        btnAtualizar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Carregando...';
        btnAtualizar.disabled = true;
        btnAtualizar.classList.add('btn-loading');
        
        historicoDiv.innerHTML = '<div class="text-center text-muted"><i class="fas fa-spinner fa-spin"></i> Carregando histórico...</div>';

        const url = '{{ route("pedido.historico") }}';

        fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            // Restaurar botão
            btnAtualizar.innerHTML = textoOriginal;
            btnAtualizar.disabled = false;
            btnAtualizar.classList.remove('btn-loading');
            
            if (data.success && data.pedidos && data.pedidos.length > 0) {
                let html = '';
                data.pedidos.forEach(pedido => {
                    // Validar dados do pedido
                    const numero = pedido.numero || 'N/A';
                    const data_pedido = pedido.data || 'N/A';
                    const nome = pedido.nome || 'Cliente';
                    const email = pedido.email || 'N/A';
                    const total = pedido.total || 0;
                    
                    // Validar endereço
                    const endereco = pedido.endereco || {};
                    const logradouro = endereco.logradouro || 'N/A';
                    const numero_endereco = endereco.numero || 'N/A';
                    const bairro = endereco.bairro || 'N/A';
                    const localidade = endereco.localidade || 'N/A';
                    const uf = endereco.uf || 'N/A';
                    
                    html += `
                        <div class="card mb-3">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Pedido #${numero}</strong>
                                    <small class="text-muted ms-2">${data_pedido}</small>
                                </div>
                                <div>
                                    <span class="badge bg-success">Finalizado</span>
                                    <strong class="ms-2">R$ ${formatarMoeda(total)}</strong>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6><i class="fas fa-user"></i> Cliente</h6>
                                        <p class="mb-1">${nome}</p>
                                        <p class="mb-0 text-muted">${email}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <h6><i class="fas fa-map-marker-alt"></i> Endereço</h6>
                                        <p class="mb-0">${logradouro}, ${numero_endereco}</p>
                                        <p class="mb-0">${bairro} - ${localidade}/${uf}</p>
                                    </div>
                                </div>
                                <hr>
                                <h6><i class="fas fa-shopping-cart"></i> Itens</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm mb-0">
                                        <tbody>
                    `;
                    
                    // Garantir que carrinho é um array
                    const carrinho = Array.isArray(pedido.carrinho) ? pedido.carrinho : [];
                    
                    if (carrinho.length > 0) {
                        carrinho.forEach(item => {
                            const nome_item = item.nome || 'Produto';
                            const variacao = item.variacao || 'N/A';
                            const quantidade = item.quantidade || 1;
                            const preco = item.preco || 0;
                            
                            html += `
                                <tr>
                                    <td>${nome_item} - ${variacao}</td>
                                    <td class="text-end">${quantidade}x</td>
                                    <td class="text-end">R$ ${formatarMoeda(preco * quantidade)}</td>
                                </tr>
                            `;
                        });
                    } else {
                        html += `
                            <tr>
                                <td colspan="3" class="text-center text-muted">Nenhum item encontrado</td>
                            </tr>
                        `;
                    }
                    
                    html += `
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    `;
                });
                historicoDiv.innerHTML = html;
                
                // Mostrar notificação de sucesso
                mostrarNotificacao('<i class="fas fa-check-circle"></i> Histórico atualizado com sucesso!', 'success', 2000);
            } else {
                historicoDiv.innerHTML = `
                    <div class="alert alert-info text-center">
                        <i class="fas fa-inbox"></i>
                        <p class="mb-0">Nenhum pedido encontrado.</p>
                        <small class="text-muted">Finalize um pedido para vê-lo aqui.</small>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            
            // Restaurar botão em caso de erro
            btnAtualizar.innerHTML = textoOriginal;
            btnAtualizar.disabled = false;
            btnAtualizar.classList.remove('btn-loading');
            
            historicoDiv.innerHTML = `
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Erro ao carregar histórico:</strong><br>
                    <small>${error.message}</small><br>
                    <button class="btn btn-sm btn-outline-primary mt-2" onclick="carregarHistoricoPedidos()">
                        <i class="fas fa-redo"></i> Tentar novamente
                    </button>
                </div>
            `;
            
            // Mostrar notificação de erro
            mostrarNotificacao('<i class="fas fa-exclamation-triangle"></i> Erro ao atualizar histórico. Tente novamente.', 'danger');
        });
    }

    // Função auxiliar para formatar moeda
    function formatarMoeda(valor) {
        return parseFloat(valor).toLocaleString('pt-BR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    // Funções para gerenciar variações de produtos
    function atualizarBotoesRemover() {
        const variacoes = document.querySelectorAll('.variacao-item');
        const botoesRemover = document.querySelectorAll('.btn-remove-variacao');
        
        // Se só há uma variação, desabilita o botão de remover
        if (variacoes.length <= 1) {
            botoesRemover.forEach(btn => {
                btn.disabled = true;
                btn.title = 'Não é possível remover a única variação';
                btn.classList.add('disabled');
            });
        } else {
            botoesRemover.forEach(btn => {
                btn.disabled = false;
                btn.title = 'Remover esta variação';
                btn.classList.remove('disabled');
            });
        }
    }

    document.getElementById('add-variacao').addEventListener('click', function () {
        const container = document.getElementById('variacoes-container');

        const variacaoItem = document.createElement('div');
        variacaoItem.classList.add('variacao-item', 'card', 'mb-2');

        variacaoItem.innerHTML = `
            <div class="card-body py-2">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <input type="text" name="variacoes[${index}][variacao]" class="form-control" 
                               placeholder="Ex: Tamanho G, Cor Verde">
                    </div>
                    <div class="col-md-4">
                        <div class="input-group">
                            <input type="number" name="variacoes[${index}][quantidade]" class="form-control" 
                                   placeholder="Quantidade" min="0" step="1">
                            <span class="input-group-text">un</span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-outline-danger btn-sm btn-remove-variacao w-100">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;

        container.appendChild(variacaoItem);

        variacaoItem.querySelector('.btn-remove-variacao').addEventListener('click', function () {
            variacaoItem.remove();
            atualizarBotoesRemover();
        });

        index++;
        atualizarBotoesRemover();
    });

    document.querySelectorAll('.btn-remove-variacao').forEach(button => {
        button.addEventListener('click', function () {
            this.closest('.variacao-item').remove();
            atualizarBotoesRemover();
        });
    });

    // Inicializar estado dos botões de remover
    atualizarBotoesRemover();

    // Formatação do campo de preço para usar vírgula
    document.getElementById('preco').addEventListener('input', function(e) {
        let valor = e.target.value;
        
        // Remove tudo que não é número, vírgula ou ponto
        valor = valor.replace(/[^\d,\.]/g, '');
        
        // Substitui ponto por vírgula
        valor = valor.replace('.', ',');
        
        // Garante apenas uma vírgula
        const partes = valor.split(',');
        if (partes.length > 2) {
            valor = partes[0] + ',' + partes.slice(1).join('');
        }
        
        // Limita a 2 casas decimais após a vírgula
        if (partes[1] && partes[1].length > 2) {
            valor = partes[0] + ',' + partes[1].substring(0, 2);
        }
        
        e.target.value = valor;
    });

    // Validação adicional para preço no submit
    document.querySelector('form').addEventListener('submit', function(e) {
        const precoInput = document.getElementById('preco');
        const valor = precoInput.value;
        
        // Verifica se o formato está correto
        if (!/^\d+([,]\d{1,2})?$/.test(valor)) {
            e.preventDefault();
            mostrarNotificacao('<i class="fas fa-exclamation-triangle"></i> Formato de preço inválido. Use vírgula para separar os decimais (ex: 19,90)', 'danger');
            precoInput.focus();
            return;
        }
    });

    // Funções para gerenciar CEP e endereço
    function buscarCEP() {
        const cep = document.getElementById('cep').value.trim();
        const resultadoDiv = document.getElementById('cepResultado');
        
        if (!cep) {
            resultadoDiv.innerHTML = '<div class="alert alert-warning"><i class="fas fa-exclamation-triangle"></i> Por favor, digite um CEP.</div>';
            return;
        }

        resultadoDiv.innerHTML = '<div class="text-info"><i class="fas fa-spinner fa-spin"></i> Buscando CEP...</div>';

        fetch('{{ route("endereco.buscar-cep") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ cep: cep })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                preencherFormularioEndereco(data.data);
                resultadoDiv.innerHTML = '<div class="alert alert-success"><i class="fas fa-check-circle"></i> CEP encontrado! Complete as informações abaixo.</div>';
                mostrarNotificacao('<i class="fas fa-map-marker-alt"></i> CEP encontrado com sucesso!', 'success', 3000);
            } else {
                resultadoDiv.innerHTML = `<div class="alert alert-danger"><i class="fas fa-times-circle"></i> ${data.message}</div>`;
                mostrarNotificacao('<i class="fas fa-exclamation-triangle"></i> ' + data.message, 'danger');
            }
        })
        .catch(error => {
            console.error('Erro ao buscar CEP:', error);
            resultadoDiv.innerHTML = '<div class="alert alert-danger"><i class="fas fa-times-circle"></i> Erro ao buscar CEP. Tente novamente.</div>';
            mostrarNotificacao('<i class="fas fa-exclamation-triangle"></i> Erro ao buscar CEP. Verifique sua conexão.', 'danger');
        });
    }

    function preencherFormularioEndereco(endereco) {
        document.getElementById('endereco-cep').value = endereco.cep;
        document.getElementById('logradouro').value = endereco.logradouro;
        document.getElementById('bairro').value = endereco.bairro;
        document.getElementById('localidade').value = endereco.localidade;
        document.getElementById('uf').value = endereco.uf;
        
        // Mostrar formulário de endereço
        document.getElementById('endereco-form').style.display = 'block';
        
        // Focar no campo número
        document.getElementById('numero').focus();
    }

    function salvarEndereco() {
        const formData = {
            cep: document.getElementById('endereco-cep').value,
            logradouro: document.getElementById('logradouro').value,
            numero: document.getElementById('numero').value,
            complemento: document.getElementById('complemento').value,
            bairro: document.getElementById('bairro').value,
            localidade: document.getElementById('localidade').value,
            uf: document.getElementById('uf').value
        };

        // Validação básica
        if (!formData.numero.trim()) {
            mostrarNotificacao('<i class="fas fa-exclamation-triangle"></i> Por favor, informe o número do endereço.', 'warning');
            document.getElementById('numero').focus();
            return;
        }

        // Mostrar loading
        const btnSalvar = document.querySelector('button[onclick="salvarEndereco()"]');
        const textoOriginal = btnSalvar.innerHTML;
        btnSalvar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Salvando...';
        btnSalvar.disabled = true;
        btnSalvar.classList.add('btn-loading');

        fetch('{{ route("endereco.salvar") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarNotificacao('<i class="fas fa-check-circle"></i> ' + data.message, 'success');
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                mostrarNotificacao('<i class="fas fa-times-circle"></i> Erro ao salvar endereço: ' + data.message, 'danger');
                btnSalvar.innerHTML = textoOriginal;
                btnSalvar.disabled = false;
                btnSalvar.classList.remove('btn-loading');
            }
        })
        .catch(error => {
            mostrarNotificacao('<i class="fas fa-times-circle"></i> Erro ao salvar endereço. Tente novamente.', 'danger');
            btnSalvar.innerHTML = textoOriginal;
            btnSalvar.disabled = false;
            btnSalvar.classList.remove('btn-loading');
        });
    }

    function cancelarEndereco() {
        document.getElementById('endereco-form').style.display = 'none';
        document.getElementById('enderecoForm').reset();
        document.getElementById('cepResultado').innerHTML = '';
    }

    function editarEndereco() {
        mostrarConfirmacao('Deseja editar o endereço de entrega? Isso removerá o endereço atual.', () => {
            fetch('{{ route("endereco.remover") }}', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    mostrarNotificacao('<i class="fas fa-times-circle"></i> Erro ao editar endereço: ' + data.message, 'danger');
                }
            })
            .catch(error => {
                mostrarNotificacao('<i class="fas fa-times-circle"></i> Erro ao editar endereço. Tente novamente.', 'danger');
            });
        });
    }

    function removerEndereco() {
        mostrarConfirmacao('Deseja realmente remover o endereço de entrega?', () => {
            fetch('{{ route("endereco.remover") }}', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarNotificacao('<i class="fas fa-check-circle"></i> Endereço removido com sucesso!', 'success');
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    mostrarNotificacao('<i class="fas fa-times-circle"></i> Erro ao remover endereço: ' + data.message, 'danger');
                }
            })
            .catch(error => {
                mostrarNotificacao('<i class="fas fa-times-circle"></i> Erro ao remover endereço. Tente novamente.', 'danger');
            });
        });
    }

    // Máscara para CEP
    document.getElementById('cep').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 5) {
            value = value.substring(0, 5) + '-' + value.substring(5, 8);
        }
        e.target.value = value;
    });

    // Permitir buscar CEP com Enter
    document.getElementById('cep').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            buscarCEP();
        }
    });

    // Carregar histórico de pedidos ao carregar a página
    document.addEventListener('DOMContentLoaded', function() {
        carregarHistoricoPedidos();
    });
</script>
@endpush
