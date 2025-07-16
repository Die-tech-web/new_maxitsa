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







}
