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

        return $result !== false ? $result : null;
    }


    public function getComptePrincipal(int $userId): ?array
    {
        $sql = "SELECT * FROM compte WHERE userid = :userId AND typecompte = 'principal' LIMIT 1";
        $stmt = $this->database->getPDO()->prepare($sql);
        $stmt->execute(['userId' => $userId]);
        $compte = $stmt->fetch(PDO::FETCH_ASSOC);

        return $compte !== false ? $compte : null;
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
    public function findById(int $id): ?array
    {
        $sql = "SELECT * FROM compte WHERE id = :id LIMIT 1";
        $stmt = $this->database->getPDO()->prepare($sql);
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
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

    public function getCompteByUserId(int $userId): ?array
    {
        $sql = "SELECT * FROM compte WHERE userid = :userId LIMIT 1";
        $stmt = $this->database->getPdo()->prepare($sql);
        $stmt->execute(['userId' => $userId]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function createTransaction(int $compteId, string $type, float $montant, string $description): array
    {
        try {
            // Commencer une transaction
            $this->beginTransaction();

            // 1. Insérer la transaction
            $sql = "INSERT INTO transaction (compteid, typeuser, montant, description, date_transaction)
                VALUES (:compteid, :typeuser, :montant, :description, NOW())";
            $stmt = $this->database->getPDO()->prepare($sql);
            $stmt->execute([
                ':compteid' => $compteId,
                ':typeuser' => $type,
                ':montant' => $montant,
                ':description' => $description
            ]);

           
            $sqlSolde = "UPDATE compte SET solde = solde - :montant WHERE id = :id";
            $stmtSolde = $this->database->getPDO()->prepare($sqlSolde);
            $stmtSolde->execute([
                ':montant' => $montant,
                ':id' => $compteId
            ]);

            // 3. Récupérer le nouveau solde
            $sqlCheck = "SELECT solde FROM compte WHERE id = :id";
            $stmtCheck = $this->database->getPDO()->prepare($sqlCheck);
            $stmtCheck->execute([':id' => $compteId]);
            $newSolde = $stmtCheck->fetch(PDO::FETCH_ASSOC)['solde'] ?? 0;

            $this->commit();

            return [
                'success' => true,
                'nouveau_solde' => $newSolde
            ];
        } catch (\Exception $e) {
            $this->rollBack();
            return [
                'success' => false,
                'message' => "Erreur transaction : " . $e->getMessage()
            ];
        }
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

    public function basculerEnprincipal(int $userId, int $compteSecondaireId): bool
    {
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