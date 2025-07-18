<?php
namespace App\Controllers;

use App\Models\Product;

/**
 * Controller responsável pelo CRUD de Produtos.
 */
class ProductController
{
    private Product $model;

    public function __construct()
    {
        $this->model = new Product();
    }

    /**
     * Exibe listagem e formulário de produtos.
     */
    public function index(): void
    {
        $produtos = $this->model->all();
        include __DIR__ . '/../views/products.php';
    }

    /**
     * Cria um novo produto.
     */
    public function store(): void
    {
        $nome = $_POST['nome'] ?? '';
        $preco = (float) ($_POST['preco'] ?? 0);
        $quantidade = (int) ($_POST['quantidade'] ?? 0);
        $variacao = $_POST['variacao'] ?? null;

        $this->model->create($nome, $preco, $quantidade, $variacao);
        header("Location: /produtos");
    }

    /**
     * Atualiza produto.
     */
    public function update(): void
    {
        $id = (int) $_POST['id'];
        $nome = $_POST['nome'] ?? '';
        $preco = (float) ($_POST['preco'] ?? 0);
        $quantidade = (int) ($_POST['quantidade'] ?? 0);
        $variacao = $_POST['variacao'] ?? null;

        $this->model->update($id, $nome, $preco, $quantidade, $variacao);
        header("Location: /produtos");
    }

    /**
     * Exclui produto.
     */
    public function destroy(): void
    {
        $id = (int) $_GET['id'];
        $this->model->delete($id);
        header("Location: /produtos");
    }
}
