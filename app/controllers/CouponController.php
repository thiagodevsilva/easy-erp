<?php
namespace App\Controllers;

use App\Models\Coupon;

/**
 * Controller para gerenciar cupons.
 */
class CouponController
{
    private Coupon $model;

    public function __construct()
    {
        $this->model = new Coupon();
    }

    /**
     * Exibe a lista de cupons e o formulário de criação.
     */
    public function index(): void
    {
        $cupons = $this->model->all();
        include __DIR__ . '/../views/coupons.php';
    }

    /**
     * Cria um novo cupom.
     */
    public function store(): void
    {
        $codigo = strtoupper(trim($_POST['codigo'] ?? ''));
        $desconto = (float) ($_POST['desconto'] ?? 0);
        $minimo = (float) ($_POST['minimo'] ?? 0);
        $validoAte = $_POST['valido_ate'] ?? date('Y-m-d');

        if ($codigo) {
            $this->model->create($codigo, $desconto, $minimo, $validoAte);
        }

        header("Location: /cupons");
    }

    /**
     * Exclui um cupom.
     */
    public function destroy(): void
    {
        $id = (int) $_GET['id'];
        $this->model->delete($id);
        header("Location: /cupons");
    }
}
