<?php
namespace App;

use PDO;
use PDOException;
use App\Helpers\Env;

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
            $host = Env::get('DB_HOST', 'localhost');
            $db   = Env::get('DB_NAME', 'easy_erp');
            $user = Env::get('DB_USER', 'root');
            $pass = Env::get('DB_PASS', '');
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
