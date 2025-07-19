<?php
namespace App\Controllers;

use App\Database;

/**
 * Controller responsável por gerenciar pedidos.
 */
class OrderController
{
    public function index(): void
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT id, nome_cliente, email_cliente, total, frete, status, criado_em FROM pedidos ORDER BY criado_em DESC");
        $pedidos = $stmt->fetchAll();

        include __DIR__ . '/../views/orders.php';
    }
}
