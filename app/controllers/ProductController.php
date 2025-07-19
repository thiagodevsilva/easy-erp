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
        $busca = $_GET['busca'] ?? '';
        $pagina = max(1, (int) ($_GET['pagina'] ?? 1));

        $resultado = $this->model->paginated($busca, $pagina, 5);
        $produtos = $resultado['produtos'];
        $totalPaginas = $resultado['totalPaginas'];

        include __DIR__ . '/../views/products.php';
    }

    /**
     * Cria um novo produto.
     */
    public function store(): void
    {
        $nome = $_POST['nome'] ?? '';
        $preco = (float) ($_POST['preco'] ?? 0);

        $variacoes = [];
        if (isset($_POST['variacao'], $_POST['quantidade'])) {
            foreach ($_POST['variacao'] as $i => $v) {
                $variacoes[] = [
                    'variacao' => $v ?: 'Padrão',
                    'quantidade' => (int) ($_POST['quantidade'][$i] ?? 0)
                ];
            }
        }

        $this->model->create($nome, $preco, $variacoes);
        header("Location: /produtos");
    }

    public function edit(): void
    {
        $id = (int) $_GET['id'];
        $produto = $this->model->find($id);

        if (!$produto) {
            http_response_code(404);
            echo "Produto não encontrado.";
            return;
        }

        include __DIR__ . '/../views/products_edit.php';
    }

    public function update(): void
    {
        $id = (int) $_POST['id'];
        $nome = $_POST['nome'];
        $preco = (float) $_POST['preco'];

        $this->model->updateProduct($id, $nome, $preco);

        // Atualiza variações existentes
        if (isset($_POST['variacao_id'])) {
            foreach ($_POST['variacao_id'] as $i => $vid) {
                $variacao = $_POST['variacao'][$i] ?? 'Padrão';
                $quantidade = (int) ($_POST['quantidade'][$i] ?? 0);
                $this->model->updateVariation((int) $vid, $variacao, $quantidade);
            }
        }

        // Adiciona novas variações (se houver)
        if (isset($_POST['nova_variacao'])) {
            foreach ($_POST['nova_variacao'] as $i => $nv) {
                $novaQuantidade = (int) ($_POST['nova_quantidade'][$i] ?? 0);
                if (trim($nv) !== '') {
                    $this->model->addVariation($id, $nv, $novaQuantidade);
                }
            }
        }

        header("Location: /produtos");
    }

    public function deleteVariation(): void
    {
        $vid = (int) $_GET['vid'];
        $this->model->deleteVariation($vid);
        header("Location: " . $_SERVER['HTTP_REFERER']);
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
