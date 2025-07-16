<?php
namespace App\Repository;
use App\Core\Database;
use PDO;

class CompteRepository extends \App\Core\Abstract\AbstractRepository
{

    public function __construct()
    {
        // $this->database = new Database();
        parent::__construct();
    }

    public function getSoldeByUserId(int $userId): ?array
    {
        $sql = "SELECT * FROM compte WHERE userid = :userId AND typecompte = 'principal' LIMIT 1";
        $stmt = $this->database->getPDO()->prepare($sql);
        $stmt->execute(['userId' => $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
}

