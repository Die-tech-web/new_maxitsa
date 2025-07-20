<?php
namespace App\Repository;
use App\Entity\Transactions;
use App\Core\Database;
use PDO;

class TransactionRepository extends \App\Core\Abstract\AbstractRepository
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getLast10Transactions($userId): array
    {
        $sql = "SELECT t.* 
        FROM transaction t
        INNER JOIN compte c ON t.compteid = c.id
        WHERE c.userid = :userid AND c.typecompte = 'principal'
        ORDER BY t.date DESC
        LIMIT 10";

        $statement = $this->database->getPdo()->prepare($sql);
        $statement->bindParam(':userid', $userId, PDO::PARAM_INT);
        $statement->execute();


        $transactions = [];
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $transactions[] = Transactions::toObject($row);

        }

        return $transactions;
    }

    public function getAllTransactions($userId): array
    {
        $sql = "SELECT t.* 
        FROM transaction t
        INNER JOIN compte c ON t.compteid = c.id
        WHERE c.userid = :userid AND c.typecompte = 'principal'
        ORDER BY t.date DESC";

        $statement = $this->database->getPdo()->prepare($sql);
        $statement->bindParam(':userid', $userId, PDO::PARAM_INT);
        $statement->execute();

        $transactions = [];
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $transactions[] = Transactions::toObject($row);
        }

        return $transactions;
    }

    //pagination // TransactionRepository.php

    public function getPaginatedTransactions(int $userId, int $page, int $limit): array
    {
        $offset = ($page - 1) * $limit;
        $sql = "SELECT t.* 
        FROM transaction t
        INNER JOIN compte c ON t.compteid = c.id
        WHERE c.userid = :userid AND c.typecompte = 'principal'
        ORDER BY t.date DESC
        LIMIT :limit OFFSET :offset";

        $statement = $this->database->getPdo()->prepare($sql);
        $statement->bindParam(':userid', $userId, PDO::PARAM_INT);
        $statement->bindParam(':limit', $limit, PDO::PARAM_INT);
        $statement->bindParam(':offset', $offset, PDO::PARAM_INT);
        $statement->execute();

        $transactions = [];
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $transactions[] = Transactions::toObject($row);
        }

        return $transactions;
    }

    public function createDepot(int $userId, float $montant, string $mode): bool
    {
        // Récupérer le compte principal de l'utilisateur
        $sqlCompte = "SELECT id FROM compte WHERE userid = :userid AND typecompte = 'principal' LIMIT 1";
        $stmt = $this->database->getPdo()->prepare($sqlCompte);
        $stmt->bindParam(':userid', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $compte = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$compte) {
            return false;
        }

        $compteId = $compte['id'];

        // Insérer la transaction
        $sql = "INSERT INTO transaction (compteid, montant, type_transaction, date, mode_paiement)
            VALUES (:compteid, :montant, 'depot', NOW(), :mode)";

        $stmt = $this->database->getPdo()->prepare($sql);
        $stmt->bindParam(':compteid', $compteId, PDO::PARAM_INT);
        $stmt->bindParam(':montant', $montant);
        $stmt->bindParam(':mode', $mode);

        return $stmt->execute();
    }




}
