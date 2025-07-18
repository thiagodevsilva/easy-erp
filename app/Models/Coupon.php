<?php
namespace App\Models;

use App\Database;
use PDO;

/**
 * Model para gerenciar Cupons.
 */
class Coupon
{
    public function all(): array
    {
        $pdo = Database::getConnection();
        return $pdo->query("SELECT * FROM cupons ORDER BY valido_ate DESC")->fetchAll();
    }

    public function create(string $codigo, float $desconto, float $minimo, string $validoAte): bool
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("INSERT INTO cupons (codigo, desconto, minimo, valido_ate) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$codigo, $desconto, $minimo, $validoAte]);
    }

    public function delete(int $id): bool
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("DELETE FROM cupons WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
