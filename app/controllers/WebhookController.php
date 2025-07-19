<?php
namespace App\Controllers;

use App\Database;
use PDO;

/**
 * Controller para processar webhooks de atualização ou cancelamento de pedidos.
 *
 * Recebe JSON via POST:
 * {
 *   "pedido_id": 1,
 *   "status": "cancelado" // ou "pago", "enviado"
 * }
 */
class WebhookController
{
    public function handle(): void
    {
        // Verifica se é POST e com JSON
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || stripos($_SERVER['CONTENT_TYPE'] ?? '', 'application/json') === false) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Requisição inválida']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        if (empty($data['pedido_id']) || empty($data['status'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Campos obrigatórios: pedido_id e status']);
            return;
        }

        $pedidoId = (int)$data['pedido_id'];
        $status = strtolower(trim($data['status']));

        $pdo = Database::getConnection();

        // Verifica se o pedido existe
        $stmt = $pdo->prepare("SELECT id FROM pedidos WHERE id = ?");
        $stmt->execute([$pedidoId]);
        if (!$stmt->fetch()) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Pedido não encontrado']);
            return;
        }

        // Atualiza o status (incluindo cancelado, pago, enviado, etc.)
        $stmt = $pdo->prepare("UPDATE pedidos SET status = ? WHERE id = ?");
        $stmt->execute([$status, $pedidoId]);

        echo json_encode(['success' => true, 'message' => "Pedido #{$pedidoId} atualizado para status '{$status}'"]);
    }
}
