<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido Finalizado</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #007bff;
            margin: 0;
        }
        .pedido-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .section-title {
            color: #007bff;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .item-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .item-table th,
        .item-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        .item-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .total-section {
            background: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            text-align: right;
        }
        .total-final {
            font-size: 1.2em;
            font-weight: bold;
            color: #007bff;
        }
        .endereco-box {
            background: #f8f9fa;
            padding: 15px;
            border-left: 4px solid #007bff;
            margin: 15px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🛍️ Pedido Finalizado</h1>
            <p>Obrigado por escolher nossa loja!</p>
        </div>

        <div class="pedido-info">
            <h3>📋 Informações do Pedido</h3>
            <p><strong>Número do Pedido:</strong> #{{ $numeroPedido }}</p>
            <p><strong>Data:</strong> {{ date('d/m/Y H:i:s') }}</p>
        </div>

        <h3 class="section-title">📦 Itens do Pedido</h3>
        <table class="item-table">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Variação</th>
                    <th>Quantidade</th>
                    <th>Preço Unit.</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($carrinho as $item)
                <tr>
                    <td>{{ $item['nome'] }}</td>
                    <td>{{ $item['variacao'] }}</td>
                    <td>{{ $item['quantidade'] }}</td>
                    <td>R$ {{ number_format($item['preco'], 2, ',', '.') }}</td>
                    <td>R$ {{ number_format($item['preco'] * $item['quantidade'], 2, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-section">
            <p><strong>Subtotal:</strong> R$ {{ number_format($subtotal, 2, ',', '.') }}</p>
            <p><strong>Frete:</strong> R$ {{ number_format($frete, 2, ',', '.') }}</p>
            <hr>
            <p class="total-final"><strong>Total:</strong> R$ {{ number_format($total, 2, ',', '.') }}</p>
        </div>

        <h3 class="section-title">📍 Endereço de Entrega</h3>
        <div class="endereco-box">
            <p><strong>{{ $endereco['logradouro'] }}, {{ $endereco['numero'] }}</strong></p>
            @if($endereco['complemento'])
                <p>{{ $endereco['complemento'] }}</p>
            @endif
            <p>{{ $endereco['bairro'] }} - {{ $endereco['localidade'] }}/{{ $endereco['uf'] }}</p>
            <p><strong>CEP:</strong> {{ $endereco['cep'] }}</p>
        </div>

        <div style="background: #d4edda; padding: 15px; border-radius: 5px; border-left: 4px solid #28a745; margin: 20px 0;">
            <h4 style="color: #155724; margin-top: 0;">✅ Pedido Confirmado!</h4>
            <p style="color: #155724; margin-bottom: 0;">
                Seu pedido foi recebido e está sendo processado.
                Você receberá atualizações sobre o status da entrega em breve.
            </p>
        </div>

        <div class="footer">
            <p>Este é um e-mail automático. Por favor, não responda.</p>
            <p><strong>Mini ERP</strong> - Sistema de Gestão</p>
        </div>
    </div>
</body>
</html>
