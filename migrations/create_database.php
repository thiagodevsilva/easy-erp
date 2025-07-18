<?php
require_once __DIR__ . '/../vendor/autoload.php';

use PDO;
use PDOException;

/**
 * Script para criar o banco de dados e tabelas do Mini ERP.
 */

// Configurações de conexão (root, sem banco ainda)
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

    // Conectar no banco criado
    $pdo->exec("USE `$dbName`");

    // Criar tabelas
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS produtos (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(255) NOT NULL,
            preco DECIMAL(10,2) NOT NULL,
            criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );

        CREATE TABLE IF NOT EXISTS estoque (
            id INT AUTO_INCREMENT PRIMARY KEY,
            produto_id INT NOT NULL,
            variacao VARCHAR(100) DEFAULT NULL,
            quantidade INT NOT NULL DEFAULT 0,
            FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE CASCADE
        );

        CREATE TABLE IF NOT EXISTS pedidos (
            id INT AUTO_INCREMENT PRIMARY KEY,
            total DECIMAL(10,2) NOT NULL,
            frete DECIMAL(10,2) NOT NULL,
            status ENUM('pendente','pago','enviado','cancelado') DEFAULT 'pendente',
            endereco TEXT NOT NULL,
            criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );

        CREATE TABLE IF NOT EXISTS cupons (
            id INT AUTO_INCREMENT PRIMARY KEY,
            codigo VARCHAR(50) UNIQUE NOT NULL,
            desconto DECIMAL(10,2) NOT NULL,
            minimo DECIMAL(10,2) DEFAULT 0,
            valido_ate DATE NOT NULL
        );
    ");

    echo "Tabelas criadas/verificadas com sucesso.\n";

} catch (PDOException $e) {
    die("Erro ao criar banco/tabelas: " . $e->getMessage());
}
