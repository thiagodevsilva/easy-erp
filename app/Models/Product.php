<?php
namespace App\Models;

use App\Database;
use PDO;

/**
 * Model para gerenciar Produtos.
 */
class Product
{
    /**
     * Retorna todos os produtos com seus estoques.
     */
    public function all(): array
    {
        $pdo = Database::getConnection();
        $sql = "SELECT p.*, e.quantidade, e.variacao 
                FROM produtos p
                LEFT JOIN estoque e ON e.produto_id = p.id";
        return $pdo->query($sql)->fetchAll();
    }

    /**
     * Cria um novo produto e estoque.
     */
    public function create(string $nome, float $preco, int $quantidade, ?string $variacao = null): bool
    {
        $pdo = Database::getConnection();
        $pdo->beginTransaction();

        try {
            $stmt = $pdo->prepare("INSERT INTO produtos (nome, preco) VALUES (?, ?)");
            $stmt->execute([$nome, $preco]);
            $produtoId = $pdo->lastInsertId();

            $stmt2 = $pdo->prepare("INSERT INTO estoque (produto_id, variacao, quantidade) VALUES (?, ?, ?)");
            $stmt2->execute([$produtoId, $variacao, $quantidade]);

            $pdo->commit();
            return true;
        } catch (\Exception $e) {
            $pdo->rollBack();
            return false;
        }
    }

    /**
     * Atualiza os dados do produto e estoque.
     */
    public function update(int $id, string $nome, float $preco, int $quantidade, ?string $variacao = null): bool
    {
        $pdo = Database::getConnection();
        $pdo->beginTransaction();

        try {
            $stmt = $pdo->prepare("UPDATE produtos SET nome = ?, preco = ? WHERE id = ?");
            $stmt->execute([$nome, $preco, $id]);

            $stmt2 = $pdo->prepare("UPDATE estoque SET variacao = ?, quantidade = ? WHERE produto_id = ?");
            $stmt2->execute([$variacao, $quantidade, $id]);

            $pdo->commit();
            return true;
        } catch (\Exception $e) {
            $pdo->rollBack();
            return false;
        }
    }

    /**
     * Exclui o produto e o estoque vinculado.
     */
    public function delete(int $id): bool
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("DELETE FROM produtos WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
