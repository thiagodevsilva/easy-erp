<?php
namespace App;

use PDO;
use PDOException;

/**
 * Class Database
 * Responsável por criar a conexão com o banco de dados MySQL (Singleton).
 */
class Database
{
    private static ?PDO $instance = null;

    /**
     * Retorna uma instância PDO conectada.
     *
     * @return PDO
     */
    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            $host = 'localhost';
            $db   = 'easy_erp';
            $user = 'root';
            $pass = '';
            $charset = 'utf8mb4';

            $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

            try {
                self::$instance = new PDO($dsn, $user, $pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]);
            } catch (PDOException $e) {
                die("Erro ao conectar ao banco: " . $e->getMessage());
            }
        }
        return self::$instance;
    }
}
