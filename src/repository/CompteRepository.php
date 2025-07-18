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

    public function ajouterSecondaire(array $data): bool
    {
        try {
            $sql = "INSERT INTO compte (numero, datecreation, solde, numerotel, typecompte, userid)
                VALUES (:numero, :datecreation, :solde, :numerotel, 'secondaire', :userid)";
            $stmt = $this->database->getPDO()->prepare($sql);

            $result = $stmt->execute([
                ':numero' => $data['numero'],
                ':datecreation' => $data['datecreation'],
                ':solde' => $data['solde'],
                ':numerotel' => $data['numerotel'],
                ':userid' => $data['userid']
            ]);

            return $result;
        } catch (Exception $e) {
            error_log("Erreur ajout compte secondaire: " . $e->getMessage());
            return false;
        }
    }
    public function findByUser(int $userId): array
    {
        $sql = "SELECT * FROM compte WHERE userid = :userId";
        $stmt = $this->database->getPDO()->prepare($sql);
        $stmt->execute(['userId' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function updateSolde(int $compteId, float $nouveauSolde): void
    {
        $sql = "UPDATE compte SET solde = :solde WHERE id = :id";
        $stmt = $this->database->getPDO()->prepare($sql);
        $stmt->execute([
            ':solde' => $nouveauSolde,
            ':id' => $compteId
        ]);
    }

    public function beginTransaction(): void
    {
        $this->database->getPDO()->beginTransaction();
    }

    public function commit(): void
    {
        $this->database->getPDO()->commit();
    }

    public function rollBack(): void
    {
        $this->database->getPDO()->rollBack();
    }


//    public function basculerEnprincipal(int $userId, int $compteSecondaireId): void
// {
//     $sql1 = "UPDATE compte SET typecompte = 'secondaire' WHERE userid = :userid AND typecompte = 'principal'";
//     $stmt1 = $this->database->getPDO()->prepare($sql1);
//     $stmt1->bindParam(':userid', $userId, \PDO::PARAM_INT);
//     $stmt1->execute();

//     $sql2 = "UPDATE compte SET typecompte = 'principal' WHERE id = :id AND userid = :userid";
//     $stmt2 = $this->database->getPDO()->prepare($sql2);
//     $stmt2->bindParam(':id', $compteSecondaireId, \PDO::PARAM_INT);
//     $stmt2->bindParam(':userid', $userId, \PDO::PARAM_INT);
//     $stmt2->execute();
// }

public function basculerEnprincipal(int $userId, int $compteSecondaireId): bool {
    try {
        $this->beginTransaction();
        
        $sql1 = "UPDATE compte SET typecompte = 'secondaire' WHERE userid = :userid AND typecompte = 'principal'";
        $stmt1 = $this->database->getPDO()->prepare($sql1);
        $stmt1->bindParam(':userid', $userId, \PDO::PARAM_INT);
        $stmt1->execute();

        $sql2 = "UPDATE compte SET typecompte = 'principal' WHERE id = :id AND userid = :userid";
        $stmt2 = $this->database->getPDO()->prepare($sql2);
        $stmt2->bindParam(':id', $compteSecondaireId, \PDO::PARAM_INT);
        $stmt2->bindParam(':userid', $userId, \PDO::PARAM_INT);
        $stmt2->execute();
        
        $this->commit();
        return true;
    } catch (\Exception $e) {
        $this->rollBack();
        return false;
    }
}



    public function getCompteInfos(int $userId): ?array
    {
        $sql = "SELECT c.*, u.nom, u.prenom 
            FROM compte c 
            JOIN users u ON c.userid = u.id 
            WHERE c.userid = :userId AND c.typecompte = 'principal' 
            LIMIT 1";
        $stmt = $this->database->getPDO()->prepare($sql);
        $stmt->execute(['userId' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}