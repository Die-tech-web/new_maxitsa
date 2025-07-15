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
            // echo "Connexion à la base de données en cours...";
            $this->pdo = new PDO(DSN, DB_USERNAME, DB_PASSWORD);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // echo "Connexion à la base de données réussie !";
        } catch (PDOException $e) {
            // echo "Erreur de connexion à la base de données : " . $e->getMessage();
        }
    }

    public function getPdo(): ?PDO{
        return $this->pdo;
    }

}
