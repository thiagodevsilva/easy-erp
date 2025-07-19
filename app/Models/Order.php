<?php
namespace App\Models;

use App\Database;

class Order
{
    /**
     * Retorna lista paginada de pedidos com filtros.
     *
     * @param string|null $status   Status do pedido (pendente, pago, cancelado ou null).
     * @param string|null $dataInicio Data inicial (Y-m-d) para filtro.
     * @param string|null $dataFim    Data final (Y-m-d) para filtro.
     * @param int $pagina             Página atual.
     * @param int $porPagina          Quantos registros por página.
     * @return array
     */
    public function paginated(?string $status, ?string $dataInicio, ?string $dataFim, int $pagina = 1, int $porPagina = 10): array
    {
        $pdo = Database::getConnection();
        $offset = ($pagina - 1) * $porPagina;

        $where = [];
        $params = [];

        if (!empty($status)) {
            $where[] = "status = ?";
            $params[] = $status;
        }
        if (!empty($dataInicio) && !empty($dataFim)) {
            $where[] = "DATE(criado_em) BETWEEN ? AND ?";
            $params[] = $dataInicio;
            $params[] = $dataFim;
        } elseif (!empty($dataInicio)) {
            $where[] = "DATE(criado_em) = ?";
            $params[] = $dataInicio;
        }

        $whereSql = $where ? "WHERE " . implode(' AND ', $where) : "";

        $sql = "SELECT id, nome_cliente, email_cliente, total, frete, status, criado_em
                FROM pedidos
                {$whereSql}
                ORDER BY criado_em DESC
                LIMIT {$porPagina} OFFSET {$offset}";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $pedidos = $stmt->fetchAll();

        $sqlCount = "SELECT COUNT(*) FROM pedidos {$whereSql}";
        $stmtCount = $pdo->prepare($sqlCount);
        $stmtCount->execute($params);
        $totalRegistros = $stmtCount->fetchColumn();
        $totalPaginas = (int) ceil($totalRegistros / $porPagina);

        return [
            'pedidos' => $pedidos,
            'totalPaginas' => $totalPaginas
        ];
    }
}
