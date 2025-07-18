<?php

namespace App\Service;

use App\Core\App;
use App\Repository\CompteRepository;
class CompteService
{
    private CompteRepository $compteRepository;

    public function __construct()
    {
        $this->compteRepository = App::getDependency('compteRepository');
    }

    public function getSolde(int $userId): ?array
    {
        return $this->compteRepository->getSoldeByUserId($userId);
    }
    public function ajouterCompteSecondaire(array $data): bool
    {
        $soldeSecondaire = $data['solde'];

        $comptePrincipal = $this->compteRepository->getSoldeByUserId($data['userid']);

        if (!$comptePrincipal || $comptePrincipal['solde'] < $soldeSecondaire) {
            return false; 
        }

        $this->compteRepository->beginTransaction();

        try {
           
            $ok = $this->compteRepository->ajouterSecondaire($data);

            if (!$ok) {
                $this->compteRepository->rollBack();
                return false;
            }

            $nouveauSolde = $comptePrincipal['solde'] - $soldeSecondaire;
            $this->compteRepository->updateSolde($comptePrincipal['id'], $nouveauSolde);

            $this->compteRepository->commit();
            return true;
        } catch (\Exception $e) {
            $this->compteRepository->rollBack();
            return false;
        }
    }

    public function getAllComptesByUserId(int $userId): array
    {
        return $this->compteRepository->findByUser($userId);
    }

//     public function basculerEnprincipal(int $userId, int $compteSecondaireId): void
// {
//     $this->compteRepository->basculerEnprincipal($userId, $compteSecondaireId);
// }
public function basculerEnprincipal(int $userId, int $compteSecondaireId): bool {
    return $this->compteRepository->basculerEnprincipal($userId, $compteSecondaireId);
}


}