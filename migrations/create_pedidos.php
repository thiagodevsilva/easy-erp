<?php
require __DIR__ . '/../vendor/autoload.php';

use App\Database;

$pdo = Database::getConnection();

// Cria tabela pedidos
$pdo->exec("
    CREATE TABLE IF NOT EXISTS pedidos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome_cliente VARCHAR(255) NOT NULL,
        email_cliente VARCHAR(255) NOT NULL,
        cep VARCHAR(10) NOT NULL,
        rua VARCHAR(255) NOT NULL,
        numero VARCHAR(20) NOT NULL,
        bairro VARCHAR(100) NOT NULL,
        cidade VARCHAR(100) NOT NULL,
        estado VARCHAR(2) NOT NULL,
        subtotal DECIMAL(10,2) NOT NULL,
        frete DECIMAL(10,2) NOT NULL,
        desconto DECIMAL(10,2) NOT NULL,
        total DECIMAL(10,2) NOT NULL,
        criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )
");

// Cria tabela pedido_itens
$pdo->exec("
    CREATE TABLE IF NOT EXISTS pedido_itens (
        id INT AUTO_INCREMENT PRIMARY KEY,
        pedido_id INT NOT NULL,
        produto_id INT NOT NULL,
        variacao VARCHAR(100) NOT NULL,
        quantidade INT NOT NULL,
        preco_unitario DECIMAL(10,2) NOT NULL,
        FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE
    )
");

echo "Migration executada com sucesso: pedidos e pedido_itens criadas.\n";
