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

        // Récupérer le compte principal
        $comptePrincipal = $this->compteRepository->getSoldeByUserId($data['userid']);

        if (!$comptePrincipal || $comptePrincipal['solde'] < $soldeSecondaire) {
            return false; // Pas assez d'argent
        }

        // Démarrer une transaction
        $this->compteRepository->beginTransaction();

        try {
            // Ajouter le compte secondaire
            $ok = $this->compteRepository->ajouterSecondaire($data);

            if (!$ok) {
                $this->compteRepository->rollBack();
                return false;
            }

            // Mettre à jour le solde du principal
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


}