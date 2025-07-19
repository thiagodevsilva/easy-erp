<?php
namespace App\Controllers;

use App\Models\Order;

class OrderController
{
    private Order $model;

    public function __construct()
    {
        $this->model = new Order();
    }

    public function index(): void
    {
        $status = $_GET['status'] ?? null;
        $dataInicio = $_GET['data_inicio'] ?? null;
        $dataFim = $_GET['data_fim'] ?? null;
        $pagina = max(1, (int) ($_GET['pagina'] ?? 1));

        $resultado = $this->model->paginated($status, $dataInicio, $dataFim, $pagina, 10);
        $pedidos = $resultado['pedidos'];
        $totalPaginas = $resultado['totalPaginas'];

        include __DIR__ . '/../views/orders.php';
    }
}
