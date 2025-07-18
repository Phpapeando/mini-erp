# 🛒 Mini ERP - Sistema de Pedidos Completo

## 🎯 **SISTEMA IMPLEMENTADO COM SUCESSO!**

### ✅ **Funcionalidades Implementadas**

1. **📦 Gestão de Produtos**
   - Criação de produtos com variações
   - Controle de estoque automático
   - Interface Bootstrap responsiva

2. **🛍️ Carrinho de Compras**
   - Adição/remoção de produtos
   - Cálculo automático de subtotal
   - Persistência em sessão

3. **📍 Sistema de Endereços**
   - Busca automática por CEP (com ou sem hífen)
   - **Dupla integração**: ViaCEP (principal) + Postmon (fallback)
   - Configuração flexível via arquivo `.env`
   - Validação completa de dados
   - Logs de monitoramento automático

4. **💰 Cálculo de Frete**
   - Frete grátis acima de R$ 200,00
   - Valores automáticos baseados no subtotal
   - Cálculo em tempo real

5. **📧 Finalização de Pedidos**
   - Modal elegante para dados do cliente
   - Validação de e-mail
   - Geração de número único do pedido
   - Envio automático de e-mail de confirmação

6. **📋 Histórico de Pedidos**
   - Listagem completa de pedidos
   - Detalhes de produtos e valores
   - Interface organizada

## 🚀 Como Rodar o Projeto Laravel pela Primeira Vez

Siga os passos abaixo para configurar o projeto localmente:

### ✅ 1. Clone o Repositório

```bash
git clone https://github.com/seu-usuario/mini-erp.git
cd mini-erp
```

### ✅ 2. Instale as Dependências PHP

Certifique-se de que você tem o [Composer](https://getcomposer.org/) instalado:

```bash
composer install
```

### ✅ 3. Copie o Arquivo `.env`

Crie o arquivo de ambiente baseado no `.env.example`:

```bash
cp .env.example .env
```

### ✅ 4. Gere a Chave da Aplicação

```bash
php artisan key:generate
```

### ✅ 5. Configure o Banco de Dados

- Edite o arquivo `.env` e ajuste as informações:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nome_do_banco
DB_USERNAME=usuario
DB_PASSWORD=senha
```

- Crie o banco de dados no seu MySQL e execute as migrations:

```bash
php artisan migrate
```

### ✅ 6. Inicie o Servidor

```bash
php artisan serve
```

- Abra no navegador:

```
http://127.0.0.1:8000
```


✨ Pronto! O sistema está no ar e pronto para uso.

---

## 🛍️ Fluxo de Compra Completo:

### 📦 **1. Gestão de Produtos**
   - **Criar Produto**: Clique em "Novo Produto"
   - **Nome e Preço**: Use formato brasileiro (ex: 19,90)
   - **Variações Múltiplas**: Adicione quantas variações quiser do mesmo produto
     - Exemplo: "Tamanho P", "Tamanho M", "Tamanho G"
     - Exemplo: "Cor Azul", "Cor Vermelha", "Cor Preta"
     - Botão "Adicionar Variação" para incluir mais linhas
   - **Estoque Individual**: Defina quantidade específica para cada variação
   - **Validação**: Sistema impede remover a última variação (mínimo 1)
   - **Salvar**: Sistema valida e salva automaticamente todas as variações

### 🛒 **2. Adicionar ao Carrinho**
   - **Escolher Variação**: Cada linha representa uma variação
   - **Definir Quantidade**: Use o campo numérico (máximo = estoque)
   - **Adicionar**: Clique no botão verde "Comprar"
   - **Visualizar**: Carrinho atualiza automaticamente
   - **Gerenciar**: Limpe o carrinho se necessário

### 📍 **3. Cadastrar Endereço de Entrega**
   - **Buscar CEP**: Digite CEP com ou sem hífen (ex: 01310-100 ou 01310100)
   - **Aguardar**: Sistema busca automaticamente via ViaCEP
   - **Completar**: Preencha número e complemento
   - **Salvar**: Endereço fica salvo na sessão
   - **Calcular Frete**: Frete é calculado automaticamente

### 💰 **4. Revisar Pedido**
   - **Subtotal**: Soma de todos os produtos
   - **Frete**: R$ 15,00 (grátis acima de R$ 200,00)
   - **Total**: Subtotal + Frete
   - **Verificar**: Botão "Finalizar" aparece automaticamente

### ✅ **5. Finalizar Pedido**
   - **Modal Elegante**: Interface profissional para dados
   - **Nome Completo**: Campo obrigatório
   - **E-mail Válido**: Validação automática
   - **Confirmar**: Sistema processa instantaneamente
   - **Número do Pedido**: Gerado automaticamente (PED-AAAAMMDD-NNNN)

### 📧 **6. Confirmação Automática**
   - **E-mail Enviado**: Confirmação automática via Mailtrap
   - **Detalhes Completos**: Produtos, endereço, valores
   - **Atualização de Estoque**: Redução automática das quantidades
   - **Carrinho Limpo**: Pronto para nova compra

### 📋 **7. Histórico de Pedidos**
   - **Visualização**: Lista todos os pedidos da sessão
   - **Detalhes**: Produtos, quantidades, valores, endereço
   - **Status**: Sempre "Finalizado" com data/hora
   - **Atualização**: Botão para recarregar histórico

---

## 💡 **Dicas Importantes**

### 🔄 **Funcionalidades Automáticas**
- ✅ **Validação de Estoque**: Impede compra acima do disponível
- ✅ **Formato Brasileiro**: Preços com vírgula (19,90)
- ✅ **Frete Grátis**: Automático acima de R$ 200,00
- ✅ **Busca de CEP**: Aceita formato com ou sem hífen
- ✅ **Numeração Única**: Pedidos com formato PED-20250719-0001
- ✅ **E-mail HTML**: Template profissional com todos os detalhes
- ✅ **Variações Ilimitadas**: Adicione quantas variações quiser por produto
- ✅ **Estoque Individual**: Cada variação tem seu próprio controle de estoque

### ⚠️ **Validações do Sistema**
- 🚫 **Carrinho Vazio**: Não permite finalizar sem produtos
- 🚫 **Sem Endereço**: Botão finalizar só aparece com endereço
- 🚫 **CEP Inválido**: Validação em tempo real
- 🚫 **E-mail Inválido**: Verificação de formato no modal
- 🚫 **Estoque Zero**: Produto não disponível para compra

### 🎯 **Cenários de Uso**

#### 🛍️ **Compra Simples (Subtotal < R$ 200)**
1. Crie produto: "Camiseta" - R$ 49,90
2. Adicione 2 unidades ao carrinho = R$ 99,80
3. Busque CEP: 01310-100
4. Total final: R$ 99,80 + R$ 15,00 = **R$ 114,80**

#### 🎁 **Compra com Frete Grátis (Subtotal ≥ R$ 200)**
1. Crie produto: "Notebook" - R$ 1.200,00
2. Adicione 1 unidade ao carrinho = R$ 1.200,00
3. Busque CEP: 20040-020
4. Total final: R$ 1.200,00 + R$ 0,00 = **R$ 1.200,00** (Frete Grátis!)

#### 📦 **Compra com Múltiplas Variações**
1. Crie produto: "Calça Jeans" - R$ 89,90
2. Adicione múltiplas variações no cadastro:
   - Clique em "Adicionar Variação" para cada nova opção
   - "P Azul" (Estoque: 5), "M Azul" (Estoque: 10), "G Azul" (Estoque: 3)
   - "P Preta" (Estoque: 8), "M Preta" (Estoque: 12), "G Preta" (Estoque: 6)
3. Salve o produto (6 variações criadas automaticamente)
4. Adicione diferentes variações ao carrinho conforme necessário
5. Sistema calcula automaticamente e controla estoque individual

---

#### **📍 CEPs para Teste**
> **💡 Dica**: Sistema usa `hybrid` por padrão (ViaCEP + Postmon fallback)  
> Para testar apenas Postmon: `CEP_SERVICE=postmon` (pode falhar devido à instabilidade da API)

- ✅ `01310-100` → Av. Paulista, São Paulo - SP
- ✅ `20040-020` → Centro, Rio de Janeiro - RJ  
- ✅ `30112-000` → Centro, Belo Horizonte - MG
- ✅ `40070-110` → Pelourinho, Salvador - BA
- ✅ `50030-230` → Boa Vista, Recife - PE
- ❌ `99999-999` → CEP inválido (para testar erro)

---

## 🏗️ **Arquitetura Implementada**

### 📋 **Padrão MVC Completo**
```
┌─ Controllers (Coordenação)
│  ├─ ProdutoController → CRUD + Validação brasileira
│  ├─ CarrinhoController → Gestão completa do carrinho  
│  ├─ EnderecoController → Busca CEP + Validação
│  └─ PedidoController → Finalização + E-mail + Histórico
│
├─ Requests (Validação)
│  ├─ ProdutoRequest → Formato brasileiro + Variações
│  ├─ BuscarCepRequest → CEP flexível (com/sem hífen)
│  ├─ AdicionarCarrinhoRequest → Validação de estoque
│  └─ FinalizarPedidoRequest → Dados do cliente
│
├─ Services (Lógica de Negócio)  
│  ├─ CarrinhoService → Cálculos + Validações
│  ├─ PedidoService → Processamento completo
│  ├─ FreteService → Cálculo automático
│  ├─ ViaCepService → Integração API principal
│  └─ PostmonCepService → Fallback de CEP
│
├─ Models (Dados)
│  ├─ Produto → Relacionamento com Estoque
│  ├─ Estoque → Variações + Quantidades  
│  ├─ Pedido → Histórico + Detalhes
│  └─ Cupom → Sistema de descontos
│
└─ Mail (Comunicação)
   └─ PedidoFinalizadoMail → Template HTML profissional
```

### 🔧 **Funcionalidades Técnicas**
- ✅ **Dependency Injection**: Services injetados via container
- ✅ **Interface Contracts**: CepServiceInterface para flexibilidade
- ✅ **Exception Handling**: Exceções customizadas específicas
- ✅ **Logging Sistema**: Monitoramento de todas as operações
- ✅ **Sessão Persistente**: Carrinho e endereço mantidos
- ✅ **Validação Robusta**: Request classes para cada operação
- ✅ **API Integration**: Múltiplos provedores de CEP com fallback
   - E-mail enviado automaticamente

## 📧 **Configuração de E-mail**
- Recomendamos usar o Mailtrap para testar o envio de e-mails no ambiente de desenvolvimento sem enviar e-mails reais.

- Acesse https://mailtrap.io e crie uma conta gratuita.

- Crie um inbox (caixa de entrada) e selecione a opção Laravel no menu de integração.

- Copie os dados SMTP fornecidos e cole no seu arquivo `.env`, como abaixo:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.seudominio.com.br
MAIL_PORT=587
MAIL_USERNAME=usuario@seudominio.com.br
MAIL_PASSWORD=senha_do_email
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=usuario@seudominio.com.br
MAIL_FROM_NAME="Mini ERP"
```

- 📘 Guia completo no site oficial:
- 👉 [Guia Laravel no Mailtrap](https://mailtrap.io/blog/laravel-mailtrap/)

## 📍 **Configuração de Serviços de CEP**

O sistema possui **três opções de provedores de CEP** com fallback automático:

### 🏆 **ViaCEP (Confiável)**
- ✅ **Gratuito e Estável**
- ✅ **Dados Completos** (logradouro, bairro, cidade, UF)
- ✅ **Alta Disponibilidade**
- 🌐 **API**: https://viacep.com.br

### 🔄 **Postmon (Alternativo)**
- ⚠️ **Instabilidade Ocasional** (Status 503 comum)
- ✅ **Interface Similar**
- ✅ **Dados Compatíveis**
- 🌐 **API**: https://postmon.com.br

### 🚀 **Hybrid (Recomendado)**
- ✅ **Melhor dos Dois Mundos**
- ✅ **ViaCEP Primeiro, Postmon como Fallback**
- ✅ **Máxima Confiabilidade**
- ✅ **Logs Detalhados de Cada Tentativa**

### ⚙️ **Como Alterar o Provedor**

#### **Opção 1: Via Arquivo .env (Recomendado)**
Edite o arquivo `.env` e altere a linha:

```env
# Para usar ViaCEP apenas
CEP_SERVICE=viacep

# Para usar Postmon apenas (não recomendado devido à instabilidade)
CEP_SERVICE=postmon

# Para usar sistema híbrido (RECOMENDADO)
CEP_SERVICE=hybrid
```

#### **Opção 2: Via Configuração**
Edite o arquivo `config/cep.php`:

```php
// Para ViaCEP
'service' => 'viacep',

// Para Postmon
'service' => 'postmon',

// Para Híbrido (recomendado)
'service' => 'hybrid',
```

### 🔧 **Configurações Adicionais**

```env
# Timeout para requisições (segundos)
CEP_TIMEOUT=10

# Serviço principal
CEP_SERVICE=viacep
```

### 📊 **Logs de Monitoramento**

O sistema registra automaticamente:
- ✅ CEPs encontrados com sucesso
- ❌ CEPs não encontrados  
- 🔄 Tentativas de fallback
- ⚠️ Erros de conexão

Verifique os logs em: `storage/logs/laravel.log`

### 🎨 **Características da Interface**

- **🎨 Design Moderno**: Bootstrap 5 com cores harmoniosas
- **✨ Animações**: Transições suaves e feedback visual
- **🔔 Notificações**: Toast messages elegantes
- **🚫 Sem Console**: Interface profissional sem logs
- **💡 UX Intuitiva**: Fluxo claro e validações

### 📊 **Exemplo de E-mail Enviado**

```html
🎉 Pedido Confirmado!

Olá [Nome],

Seu pedido PED-20250718-0001 foi confirmado com sucesso!

📦 Produtos:
- Smartphone Samsung Galaxy (Qtd: 1) - R$ 150,00

📍 Endereço de Entrega:
Av. Paulista, 1578
Bela Vista, São Paulo - SP
CEP: 01310-100

💰 Resumo Financeiro:
Subtotal: R$ 150,00
Frete: R$ 15,00
Total: R$ 165,00

Obrigado pela preferência!
Mini ERP Team
```

### 🔧 **Arquivos Principais**

```
app/Http/Controllers/
├── ProdutoController.php    # CRUD de produtos
├── CarrinhoController.php   # Gestão do carrinho
├── EnderecoController.php   # Busca CEP e endereços
└── PedidoController.php     # Finalização de pedidos

app/Mail/
└── PedidoFinalizadoMail.php # Template de e-mail

resources/views/
├── produtos/index.blade.php              # Interface principal
└── emails/pedido-finalizado.blade.php    # Template de e-mail

routes/web.php               # Rotas da aplicação
```

### 🚀 **Próximos Passos Sugeridos:**

- [ ] Implementar pagamento (PIX/Cartão)
- [ ] Dashboard administrativo
- [ ] Relatórios de vendas
- [ ] Sistema de cupons
- [ ] Notificações push
- [ ] API REST para mobile

---

*Desenvolvido com ❤️ usando Laravel + Bootstrap*
