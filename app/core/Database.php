<?php
namespace App\Core;
use PDO;
use PDOException;

class Database
{
    private ?PDO $pdo;

    public function __construct()
    {
        try {
            $this->pdo = new PDO(DSN, DB_USERNAME, DB_PASSWORD);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
        }
    }

    public function getPdo(): ?PDO
    {
        return $this->pdo;
    }

}
