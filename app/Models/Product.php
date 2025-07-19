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
        $sql = "SELECT p.id as produto_id, p.nome, p.preco, p.criado_em,
                    e.id as estoque_id, e.variacao, e.quantidade
                FROM produtos p
                LEFT JOIN estoque e ON e.produto_id = p.id
                ORDER BY p.id ASC";
        $rows = $pdo->query($sql)->fetchAll();

        $produtos = [];
        foreach ($rows as $row) {
            $id = $row['produto_id'];
            if (!isset($produtos[$id])) {
                $produtos[$id] = [
                    'id' => $id,
                    'nome' => $row['nome'],
                    'preco' => $row['preco'],
                    'criado_em' => $row['criado_em'],
                    'variacoes' => []
                ];
            }
            if ($row['estoque_id']) {
                $produtos[$id]['variacoes'][] = [
                    'estoque_id' => $row['estoque_id'],
                    'variacao' => $row['variacao'],
                    'quantidade' => $row['quantidade']
                ];
            }
        }
        return array_values($produtos);
    }

    /**
     * Cria um novo produto e estoque.
     */
    public function create(string $nome, float $preco, array $variacoes): bool
    {
        $pdo = Database::getConnection();
        $pdo->beginTransaction();

        try {
            $stmt = $pdo->prepare("INSERT INTO produtos (nome, preco) VALUES (?, ?)");
            $stmt->execute([$nome, $preco]);
            $produtoId = $pdo->lastInsertId();

            $stmtEstoque = $pdo->prepare("INSERT INTO estoque (produto_id, variacao, quantidade) VALUES (?, ?, ?)");
            foreach ($variacoes as $v) {
                $stmtEstoque->execute([$produtoId, $v['variacao'], $v['quantidade']]);
            }

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

    public function find(int $id): ?array
    {
        $pdo = Database::getConnection();
        $sql = "SELECT p.id as produto_id, p.nome, p.preco, p.criado_em,
                    e.id as estoque_id, e.variacao, e.quantidade
                FROM produtos p
                LEFT JOIN estoque e ON e.produto_id = p.id
                WHERE p.id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $rows = $stmt->fetchAll();

        if (!$rows) {
            return null;
        }

        $produto = [
            'id' => $rows[0]['produto_id'],
            'nome' => $rows[0]['nome'],
            'preco' => $rows[0]['preco'],
            'variacoes' => []
        ];

        foreach ($rows as $row) {
            if ($row['estoque_id']) {
                $produto['variacoes'][] = [
                    'estoque_id' => $row['estoque_id'],
                    'variacao' => $row['variacao'],
                    'quantidade' => $row['quantidade']
                ];
            }
        }

        return $produto;
    }

    public function updateProduct(int $id, string $nome, float $preco): bool
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("UPDATE produtos SET nome = ?, preco = ? WHERE id = ?");
        return $stmt->execute([$nome, $preco, $id]);
    }

    public function updateVariation(int $estoqueId, string $variacao, int $quantidade): bool
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("UPDATE estoque SET variacao = ?, quantidade = ? WHERE id = ?");
        return $stmt->execute([$variacao, $quantidade, $estoqueId]);
    }

    public function addVariation(int $produtoId, string $variacao, int $quantidade): bool
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("INSERT INTO estoque (produto_id, variacao, quantidade) VALUES (?, ?, ?)");
        return $stmt->execute([$produtoId, $variacao, $quantidade]);
    }

    public function deleteVariation(int $estoqueId): bool
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("DELETE FROM estoque WHERE id = ?");
        return $stmt->execute([$estoqueId]);
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

    /**
     * Retorna o filtro e o total de páginas
     *
     * @param string $busca
     * @param integer $pagina
     * @param integer $porPagina
     * @return array
     */
    public function paginated(string $busca = '', int $pagina = 1, int $porPagina = 5): array
    {
        $pdo = Database::getConnection();

        $offset = ($pagina - 1) * $porPagina;
        $params = [];
        $where = '';

        if (!empty($busca)) {
            $where = "WHERE p.nome LIKE ?";
            $params[] = "%{$busca}%";
        }

        $sql = "SELECT p.id as produto_id, p.nome, p.preco, p.criado_em,
                    e.id as estoque_id, e.variacao, e.quantidade
                FROM produtos p
                LEFT JOIN estoque e ON e.produto_id = p.id
                {$where}
                ORDER BY p.id DESC
                LIMIT {$porPagina} OFFSET {$offset}";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll();

        $sqlCount = "SELECT COUNT(*) FROM produtos p {$where}";
        $stmtCount = $pdo->prepare($sqlCount);
        $stmtCount->execute($params);
        $totalRegistros = $stmtCount->fetchColumn();
        $totalPaginas = (int) ceil($totalRegistros / $porPagina);

        $produtos = [];
        foreach ($rows as $row) {
            $id = $row['produto_id'];
            if (!isset($produtos[$id])) {
                $produtos[$id] = [
                    'id' => $id,
                    'nome' => $row['nome'],
                    'preco' => $row['preco'],
                    'criado_em' => $row['criado_em'],
                    'variacoes' => []
                ];
            }
            if ($row['estoque_id']) {
                $produtos[$id]['variacoes'][] = [
                    'estoque_id' => $row['estoque_id'],
                    'variacao' => $row['variacao'],
                    'quantidade' => $row['quantidade']
                ];
            }
        }

        return [
            'produtos' => array_values($produtos),
            'totalPaginas' => $totalPaginas
        ];
    }
}
