<?php
namespace App\Controllers\database;
use PDO;
use PDOException;

class Database {
    private static ?Database $instance = null;
    private PDO $pdo;

    private function __construct() {
        $host = 'localhost';
        $db = 'actualites';
        $user = 'root';
        $pass = ''; // Mot de passe vide par défaut pour WAMP
        $port = '3308';
        $charset = 'utf8';

        $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public static function getInstance(): Database {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection(): PDO {
        return $this->pdo;
    }

    // Méthode pour exécuter des requêtes SELECT
    public function query(string $sql, array $params = []): PDOStatement {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    // Méthode pour exécuter des requêtes INSERT, UPDATE, DELETE
    public function execute(string $sql, array $params = []): bool {
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    // Méthode pour obtenir le dernier ID inséré
    public function lastInsertId(): string {
        return $this->pdo->lastInsertId();
    }
}
