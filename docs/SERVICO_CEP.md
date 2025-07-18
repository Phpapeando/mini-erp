# Serviço de CEP - Mini ERP

Este projeto implementa um sistema desacoplado para busca de CEP, permitindo fácil troca entre diferentes provedores de serviço.

## Arquitetura

O sistema utiliza o padrão **Strategy** com **Dependency Injection** para permitir a troca fácil entre diferentes serviços de CEP.

### Componentes:

1. **Interface**: `CepServiceInterface` - Define o contrato que todos os serviços devem implementar
2. **Serviços Concretos**: Implementações específicas para cada provedor (ViaCEP, Postmon, etc.)
3. **Controller**: `EnderecoController` - Gerencia as requisições relacionadas a endereço
4. **Configuração**: `config/cep.php` - Centraliza as configurações dos serviços

## Serviços Disponíveis

### 1. ViaCEP (Padrão)
- **URL**: https://viacep.com.br/
- **Gratuito**: Sim
- **Limite**: Sem limite oficial
- **Confiabilidade**: Alta

### 2. Postmon
- **URL**: https://api.postmon.com.br/
- **Gratuito**: Sim
- **Limite**: Sem limite oficial
- **Confiabilidade**: Média

## Como Usar

### Buscar CEP via API
```javascript
fetch('/endereco/buscar-cep', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': token
    },
    body: JSON.stringify({ cep: '01310-100' })
})
```

### Salvar Endereço na Sessão
```javascript
fetch('/endereco/salvar', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': token
    },
    body: JSON.stringify({
        cep: '01310-100',
        logradouro: 'Avenida Paulista',
        numero: '1000',
        complemento: 'Apto 123',
        bairro: 'Bela Vista',
        localidade: 'São Paulo',
        uf: 'SP'
    })
})
```

## Como Trocar de Serviço

### Método 1: Via Configuração (Recomendado)

No arquivo `.env`, adicione:
```env
CEP_SERVICE=postmon
```

Ou no arquivo `config/cep.php`:
```php
'service' => 'postmon',
```

### Método 2: Via ServiceProvider

No arquivo `app/Providers/AppServiceProvider.php`:
```php
$this->app->bind(CepServiceInterface::class, PostmonCepService::class);
```

## Criando um Novo Serviço

### 1. Criar a Classe do Serviço

```php
<?php

namespace App\Services;

use App\Contracts\CepServiceInterface;

class MeuNovoServicoService implements CepServiceInterface
{
    public function buscarCep(string $cep): ?array
    {
        // Implementar lógica de busca
        // Deve retornar array no formato padrão ou null
    }

    public function validarCep(string $cep): bool
    {
        // Implementar validação do CEP
    }
}
```

### 2. Registrar no Arquivo de Configuração

No arquivo `config/cep.php`:
```php
'services' => [
    'viacep' => \App\Services\ViaCepService::class,
    'postmon' => \App\Services\PostmonCepService::class,
    'meuservico' => \App\Services\MeuNovoServicoService::class, // Novo serviço
],
```

### 3. Configurar para Usar

No arquivo `.env`:
```env
CEP_SERVICE=meuservico
```

## Formato de Resposta Padrão

Todos os serviços devem retornar um array no seguinte formato:

```php
[
    'cep' => '01310-100',
    'logradouro' => 'Avenida Paulista',
    'complemento' => '',
    'bairro' => 'Bela Vista',
    'localidade' => 'São Paulo',
    'uf' => 'SP',
    'ibge' => '3550308',
    'gia' => '1004',
    'ddd' => '11',
    'siafi' => '7107'
]
```

## Benefícios da Arquitetura

1. **Desacoplamento**: Fácil troca entre provedores
2. **Testabilidade**: Pode usar mocks para testes
3. **Flexibilidade**: Suporte a múltiplos provedores
4. **Manutenibilidade**: Código organizado e limpo
5. **Escalabilidade**: Fácil adição de novos serviços

## Logs

Todos os erros são registrados automaticamente nos logs do Laravel:
- Falhas de requisição
- CEPs inválidos
- Timeouts
- Exceções gerais

## Configurações Avançadas

### Timeout
```env
CEP_TIMEOUT=15
```

### Service Provider Customizado
Você pode criar seu próprio Service Provider para lógicas mais complexas de seleção de serviço:

```php
class CepServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(CepServiceInterface::class, function ($app) {
            // Lógica customizada para escolher o serviço
            // Ex: balanceamento de carga, fallback, etc.
        });
    }
}
```
