<?php
namespace App\Models;

use App\Database;
use PDO;

/**
 * Model para gerenciar os Pedidos.
 */
class Pedido
{
    /**
     * Cria um novo pedido e retorna o ID gerado.
     *
     * @param array $cliente Dados do cliente (nome, email, endereço).
     * @param array $totais  Totais do pedido (subtotal, frete, desconto, total).
     * @param array $itens   Itens do carrinho (produto_id, variacao, quantidade, preco_unitario).
     * @return int|null      ID do pedido criado ou null em caso de erro.
     */
    public function criar(array $cliente, array $totais, array $itens): ?int
    {
        $pdo = Database::getConnection();
        $pdo->beginTransaction();

        try {
            // Cria o pedido
            $stmt = $pdo->prepare("
                INSERT INTO pedidos 
                (nome_cliente, email_cliente, cep, rua, numero, bairro, cidade, estado,
                    subtotal, frete, desconto, total) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $cliente['nome'],
                $cliente['email'],
                $cliente['cep'],
                $cliente['rua'],
                $cliente['numero'],
                $cliente['bairro'],
                $cliente['cidade'],
                $cliente['estado'],
                $totais['subtotal'],
                $totais['frete'],
                $totais['desconto'],
                $totais['total']
            ]);

            $pedidoId = (int) $pdo->lastInsertId();

            // Insere os itens
            $stmtItem = $pdo->prepare("
                INSERT INTO pedido_itens (pedido_id, produto_id, variacao, quantidade, preco_unitario)
                VALUES (?, ?, ?, ?, ?)
            ");

            foreach ($itens as $item) {
                $stmtItem->execute([
                    $pedidoId,
                    $item['produto_id'],
                    $item['variacao'],
                    $item['quantidade'],
                    $item['preco']
                ]);

                // Dá baixa no estoque
                $stmtEstoque = $pdo->prepare("
                    UPDATE estoque 
                    SET quantidade = GREATEST(0, quantidade - ?) 
                    WHERE produto_id = ? AND variacao = ?
                ");
                $stmtEstoque->execute([
                    $item['quantidade'],
                    $item['produto_id'],
                    $item['variacao']
                ]);
            }

            $pdo->commit();
            return $pedidoId;

        } catch (\Exception $e) {
            $pdo->rollBack();
            var_dump("ERRO ao salvar pedido:", $e->getMessage());
            exit;
        }
    }
}
