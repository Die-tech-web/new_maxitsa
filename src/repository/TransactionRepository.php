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

    // Méthode pour compter les transactions (nécessaire pour la pagination)
    public function countTransactions(int $userId): int
    {
        $sql = "SELECT COUNT(*) as total 
        FROM transaction t
        INNER JOIN compte c ON t.compteid = c.id
        WHERE c.userid = :userid AND c.typecompte = 'principal'";

        $statement = $this->database->getPdo()->prepare($sql);
        $statement->bindParam(':userid', $userId, PDO::PARAM_INT);
        $statement->execute();
        
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return (int) $result['total'];
    }

    public function createDepot(int $userId, float $montant, string $type): bool
    {
        $sqlCompte = "SELECT id FROM compte WHERE userid = :userid AND typecompte = 'principal' LIMIT 1";
        $stmt = $this->database->getPdo()->prepare($sqlCompte);
        $stmt->bindParam(':userid', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $compte = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$compte) {
            return false;
        }

        $compteId = $compte['id'];

        $sql = "INSERT INTO transaction (compteid, montant, typetransaction, date)
            VALUES (:compteid, :montant, :typetransaction, NOW())";

        $stmt = $this->database->getPdo()->prepare($sql);
        $stmt->bindParam(':compteid', $compteId, PDO::PARAM_INT);
        $stmt->bindParam(':montant', $montant);
        $stmt->bindParam(':typetransaction', $type);

        return $stmt->execute();
    }

    public function getComptePrincipal(int $userId): ?array
    {
        $sql = "SELECT * FROM compte WHERE userid = :userid AND typecompte = 'principal' LIMIT 1";
        $stmt = $this->database->getPdo()->prepare($sql);
        $stmt->bindParam(':userid', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function storeDepot(array $transaction, array $compte): bool
    {
        $pdo = $this->database->getPdo();

        try {
            $pdo->beginTransaction();

            // Insérer la transaction
            $stmt = $pdo->prepare("INSERT INTO transaction (compteid, montant, typetransaction, date) VALUES (:compteid, :montant, :type, :date)");
            $stmt->execute([
                ':compteid' => $transaction['compte_id'],
                ':montant' => $transaction['montant'],
                ':type' => $transaction['type'],
                ':date' => $transaction['date'],
            ]);

            // Mettre à jour le solde du compte
            $stmt = $pdo->prepare("UPDATE compte SET solde = :solde WHERE id = :id");
            $stmt->execute([
                ':solde' => $compte['solde'],
                ':id' => $compte['id'],
            ]);

            $pdo->commit();
            return true;
        } catch (\Exception $e) {
            $pdo->rollBack();
            error_log("Erreur dans storeDepot: " . $e->getMessage());
            return false;
        }
    }

    // Méthode manquante ajoutée
    public function saveTransaction(float $montant, string $type, string $date, int $compteId): bool
    {
        try {
            $sql = "INSERT INTO transaction (compteid, montant, typetransaction, date) VALUES (:compteid, :montant, :type, :date)";
            $stmt = $this->database->getPdo()->prepare($sql);
            
            return $stmt->execute([
                ':compteid' => $compteId,
                ':montant' => $montant,
                ':type' => $type,
                ':date' => $date,
            ]);
        } catch (\Exception $e) {
            error_log("Erreur dans saveTransaction: " . $e->getMessage());
            return false;
        }
    }
}