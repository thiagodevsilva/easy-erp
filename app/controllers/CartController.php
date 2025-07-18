<?php
namespace App\Controllers;

use App\Database;

/**
 * Controller para gerenciar o Carrinho de Compras.
 */
class CartController
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['carrinho'])) {
            $_SESSION['carrinho'] = [];
        }
    }

    /**
     * Exibe o carrinho.
     */
    public function index(): void
    {
        $items = $_SESSION['carrinho'];
        include __DIR__ . '/../views/cart.php';
    }

    /**
     * Adiciona um item ao carrinho.
     */
    public function add(): void
    {
        $produtoId = (int) ($_POST['produto_id'] ?? 0);
        $variacao = $_POST['variacao'] ?? '';
        $quantidade = max(1, (int) ($_POST['quantidade'] ?? 1));

        if (!$produtoId) {
            header("Location: /produtos");
            exit;
        }

        // Verifica se item já está no carrinho
        $key = $produtoId . '-' . $variacao;
        if (isset($_SESSION['carrinho'][$key])) {
            $_SESSION['carrinho'][$key]['quantidade'] += $quantidade;
        } else {
            // Busca detalhes do produto
            $pdo = Database::getConnection();
            $sql = "SELECT p.id, p.nome, p.preco, e.variacao 
                    FROM produtos p
                    LEFT JOIN estoque e ON e.produto_id = p.id AND e.variacao = ?
                    WHERE p.id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$variacao, $produtoId]);
            $produto = $stmt->fetch();

            if ($produto) {
                $_SESSION['carrinho'][$key] = [
                    'produto_id' => $produto['id'],
                    'nome' => $produto['nome'],
                    'variacao' => $produto['variacao'] ?? 'Padrão',
                    'preco' => $produto['preco'],
                    'quantidade' => $quantidade
                ];
            }
        }

        header("Location: /carrinho");
    }

    /**
     * Atualiza quantidade de um item.
     */
    public function update(): void
    {
        $key = $_POST['key'] ?? '';
        $quantidade = max(1, (int) ($_POST['quantidade'] ?? 1));

        if (isset($_SESSION['carrinho'][$key])) {
            $_SESSION['carrinho'][$key]['quantidade'] = $quantidade;
        }

        header("Location: /carrinho");
    }

    /**
     * Remove um item do carrinho.
     */
    public function remove(): void
    {
        $key = $_GET['key'] ?? '';
        unset($_SESSION['carrinho'][$key]);
        header("Location: /carrinho");
    }
}
