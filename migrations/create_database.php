<?php
require_once __DIR__ . '/../vendor/autoload.php';

use PDO;
use PDOException;

/**
 * Migration para criar o banco de dados e todas as tabelas do Mini ERP.
 */
$host = 'localhost';
$user = 'root';
$pass = 'Thiago@2022';
$dbName = 'easy_erp';

try {
    // Conexão inicial
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    // Criar banco se não existir
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "Banco de dados `$dbName` verificado/criado com sucesso.\n";

    // Conectar no banco
    $pdo->exec("USE `$dbName`");

    // Dropar tabelas antigas para evitar conflitos
    $pdo->exec("DROP TABLE IF EXISTS pedido_itens");
    $pdo->exec("DROP TABLE IF EXISTS pedidos");
    $pdo->exec("DROP TABLE IF EXISTS estoque");
    $pdo->exec("DROP TABLE IF EXISTS produtos");
    $pdo->exec("DROP TABLE IF EXISTS cupons");

    // Criar tabelas finais
    $pdo->exec("
        CREATE TABLE produtos (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(255) NOT NULL,
            preco DECIMAL(10,2) NOT NULL,
            criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );

        CREATE TABLE estoque (
            id INT AUTO_INCREMENT PRIMARY KEY,
            produto_id INT NOT NULL,
            variacao VARCHAR(100) DEFAULT NULL,
            quantidade INT NOT NULL DEFAULT 0,
            FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE CASCADE
        );

        CREATE TABLE cupons (
            id INT AUTO_INCREMENT PRIMARY KEY,
            codigo VARCHAR(50) UNIQUE NOT NULL,
            desconto DECIMAL(10,2) NOT NULL,
            minimo DECIMAL(10,2) DEFAULT 0,
            valido_ate DATE NOT NULL
        );

        CREATE TABLE pedidos (
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
            status ENUM('pendente','pago','enviado','cancelado') DEFAULT 'pendente',
            criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );

        CREATE TABLE pedido_itens (
            id INT AUTO_INCREMENT PRIMARY KEY,
            pedido_id INT NOT NULL,
            produto_id INT NOT NULL,
            variacao VARCHAR(100) NOT NULL,
            quantidade INT NOT NULL,
            preco_unitario DECIMAL(10,2) NOT NULL,
            FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE
        );
    ");

    echo "Todas as tabelas criadas com sucesso.\n";

} catch (PDOException $e) {
    die("Erro ao criar banco/tabelas: " . $e->getMessage());
}
