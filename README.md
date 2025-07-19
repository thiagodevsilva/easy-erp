# Easy ERP - Easy ERP em PHP Puro

Este projeto é um **mini ERP** desenvolvido em **PHP Puro (MVC)** com **Bootstrap** e **MySQL**, para gestão de:

- Produtos (com variações e controle de estoque)
- Pedidos (com frete dinâmico e cupons)
- Carrinho de compras (com sessão)
- Cupons de desconto
- Perfil do usuário (nome, e-mail e foto salvos em sessão)
- Envio de e-mail de confirmação via SMTP (PHPMailer)

---

## Requisitos
- PHP 7.4+
- Composer
- MySQL 5.7+
- Servidor local (Laragon, XAMPP, etc.)

---

## Instalação

1. Clone o repositório:
   ```bash
   git clone https://github.com/thiagodevsilva/easy-erp
   cd easy-erp
   ```

2. Instale as dependências:
   ```bash
   composer install
   ```

3. Crie o arquivo `.env` baseado no exemplo:
   ```bash
   cp .env.example .env
   ```
   Configure os dados do banco de dados e SMTP.

4. Crie o banco e tabelas:
   ```bash
   php migrations/create_database.php
   ```

5. Inicie o servidor PHP:
   ```bash
   php -S localhost:8000 -t public
   ```

6. Acesse o sistema:
   ```
   http://localhost:8000
   ```

---

## Rotas Principais

### Produtos
- `GET /produtos` - Lista produtos e variações
- `POST /produtos/criar` - Cria produto e variações
- `POST /produtos/atualizar` - Atualiza produto e estoque
- `POST /produtos/remover-variacao` - Remove uma variação
- `POST /produtos/deletar` - Deleta produto

### Carrinho
- `GET /carrinho` - Exibe carrinho
- `POST /carrinho/adicionar` - Adiciona produto
- `POST /carrinho/atualizar` - Atualiza quantidades
- `GET /carrinho/remover` - Remove item
- `POST /carrinho/aplicar-cupom` - Aplica cupom
- `GET /carrinho/remover-cupom` - Remove cupom aplicado

### Checkout
- `GET /checkout` - Tela para dados do cliente
- `POST /checkout/confirmar` - Tela de confirmação do pedido
- `POST /checkout/finalizar` - Finaliza o pedido (salva no banco e envia e-mail)

### Pedidos (Painel de Gestão)
- `GET /pedidos` - Lista todos os pedidos com **cores por status**:
  - **Amarelo** para pendentes,
  - **Verde** para pagos,
  - **Cinza** para cancelados.
- **Filtros disponíveis:**
  - Por **status** (pendente, pago, cancelado) — **cancelados ficam ocultos por padrão**.
  - Por **intervalo de datas** ou **data única** (o usuário pode escolher o range).

A tela permite **navegar pelos pedidos rapidamente** e é usada para **monitoramento e gestão de vendas**.


### Cupons
- `GET /cupons` - Lista cupons
- `POST /cupons/criar` - Cria cupom
- `POST /cupons/deletar` - Deleta cupom

### Webhook (Pedidos)
Atualiza o status dos pedidos

- `POST /webhook`  
  Recebe JSON:
  ```json
  {
    "pedido_id": 1,
    "status": "cancelado"
  }
  ```
  Ou
  ```json
  {
    "pedido_id": 2,
    "status": "pago"
  }
  ```
  Ou
  ```json
  {
    "pedido_id": 3,
    "status": "enviado"
  }
  ```
  E etc..

---

## Envio de E-mails
- Usa **PHPMailer** com SMTP.
- Configure no `.env`:
  ```env
  MAIL_HOST=smtp.gmail.com
  MAIL_PORT=587
  MAIL_USER=seuemail@gmail.com
  MAIL_PASS=sua_senha_de_app
  MAIL_FROM=seuemail@gmail.com
  MAIL_FROM_NAME=Easy ERP
  ```

Para Gmail, crie uma **Senha de App**:
[https://myaccount.google.com/apppasswords](https://myaccount.google.com/apppasswords)

---

## Estrutura
```
app/
  Controllers/    # Lógica de controle (MVC)
  Models/         # Modelos (PDO)
  Helpers/        # Env, Mailer, etc.
  Views/          # Páginas (Bootstrap)
  Database.php    # Conexão MySQL

public/
  index.php       # Roteador
  .htaccess       # URLs amigáveis

migrations/
  create_database.php  # Cria banco e tabelas

.env.example      # Exemplo de configuração
```

---

## Fluxo do Pedido
1. Adicionar produtos ao carrinho.
2. Preencher dados no checkout (`/checkout`).
3. Confirmar pedido (`/checkout/confirmar`).
4. Finalizar (`/checkout/finalizar`) →  
   - Salva no banco (`pedidos` e `pedido_itens`).  
   - Dá baixa no estoque.  
   - Envia e-mail de confirmação ao cliente.

---

## Observações
- O projeto usa **sessões para o carrinho e perfil do usuário**.
- **Frete dinâmico**:
  - Subtotal entre R$52,00 e R$166,59 → R$15,00.
  - Subtotal acima de R$200,00 → Grátis.
  - Outros valores → R$20,00.
- Cupom tem **validade e valor mínimo**.
- **Pedidos cancelados** ficam ocultos por padrão na listagem, só aparecem se o usuário filtrar explicitamente.
- **Logout** limpa toda a sessão (perfil + carrinho) e pede confirmação se houver itens no carrinho.

---

## Próximos Passos
- Melhorar a experiência de usuário com Ajax (carrinho dinâmico).
- Adicionar relatórios de vendas e métricas.
- Implementar autenticação real com banco (usuários e senhas).
