<?php
namespace App\Service;

use App\Core\App;
use App\Core\Session;
use App\Repository\TransactionRepository;
use App\Repository\CompteRepository;

class TransactionService
{
    private TransactionRepository $transactionsRepo;
    private CompteRepository $compteRepository;

    public function __construct()
    {
        $this->transactionsRepo = App::getDependency('transactionRepository');
        $this->compteRepository = App::getDependency('compteRepository');
    }

    public function getLast10Transactions(int $userId)
    {
        return $this->transactionsRepo->getLast10Transactions($userId);
    }

    public function getAllTransactions(int $userId): array
    {
        return $this->transactionsRepo->getAllTransactions($userId);
    }

    public function createDepot(int $userId, float $montant, string $type): bool
    {
        try {
            $compte = $this->compteRepository->getComptePrincipal($userId);

            if (!$compte) {
                Session::getInstance()->set('errors', ['Aucun compte principal trouvé.']);
                return false;
            }

            $frais = 0;
            if ($type === 'depot') {
                $frais = $montant * 0.0085;
            } elseif ($type === 'depot_principal_to_principal') {
                $frais = min($montant * 0.08, 5000);
            }

            $total = $montant + $frais;

            if ($compte['solde'] < $total) {
                Session::getInstance()->set('errors', ['Solde insuffisant pour couvrir la transaction et les frais.']);
                return false;
            }

            $transaction = [
                'compte_id' => $compte['id'],
                'montant' => $montant,
                'type' => $type,
                'date' => date('Y-m-d H:i:s'),
            ];

            $compte['solde'] -= $total;

            // Enregistrer la transaction et mettre à jour le solde
            return $this->transactionsRepo->storeDepot($transaction, $compte);

        } catch (\Exception $e) {
            error_log("Erreur dans createDepot: " . $e->getMessage());
            Session::getInstance()->set('errors', ['Une erreur est survenue lors du traitement.']);
            return false;
        }
    }

    /**
     * Nouvelle méthode pour créer un dépôt avec sélection de compte
     */
    public function createDepotAvecCompte(int $compteSourceId, float $montant, string $type): bool
    {
        try {
            // Récupérer le compte source
            $compteSource = $this->transactionsRepo->getCompteById($compteSourceId);
            
            if (!$compteSource) {
                Session::getInstance()->set('errors', ['Compte source non trouvé.']);
                return false;
            }

            // Calculer les frais selon le type de compte et de transaction
            $frais = $this->calculerFrais($compteSource['typecompte'], $type, $montant);
            $total = $montant + $frais;

            // Vérifier le solde disponible
            if ($compteSource['solde'] < $total) {
                Session::getInstance()->set('errors', [
                    "Solde insuffisant. Besoin: {$total} FCFA, Disponible: {$compteSource['solde']} FCFA"
                ]);
                return false;
            }

            // Préparer les données de transaction
            $transaction = [
                'compte_id' => $compteSource['id'],
                'montant' => $montant,
                'type' => $type,
                'date' => date('Y-m-d H:i:s'),
            ];

            // Nouveau solde après déduction
            $compteSource['nouveau_solde'] = $compteSource['solde'] - $total;

            // Enregistrer la transaction et mettre à jour le solde
            return $this->transactionsRepo->storeDepotAvecCompte($transaction, $compteSource);

        } catch (\Exception $e) {
            error_log("Erreur dans createDepotAvecCompte: " . $e->getMessage());
            Session::getInstance()->set('errors', ['Une erreur est survenue lors du traitement.']);            return false;
        }
    }

    /**
     * Calcule les frais selon le type de compte et de transaction
     */
    private function calculerFrais(string $typeCompte, string $typeTransaction, float $montant): float
    {
        $frais = 0;

        switch ($typeTransaction) {
            case 'depot':
                if ($typeCompte === 'principal') {
                    $frais = $montant * 0.0085; // 0,85% pour dépôt depuis compte principal
                }
                // Pas de frais pour dépôt depuis compte secondaire
                break;

            case 'depot_principal_to_principal':
                $frais = min($montant * 0.08, 5000); // 8% max 5000 FCFA
                break;

            case 'retrait':
                // Définir les frais de retrait selon vos règles métier
                if ($typeCompte === 'principal') {
                    $frais = $montant * 0.01; // 1% par exemple
                } else {
                    $frais = $montant * 0.005; // 0,5% pour compte secondaire
                }
                break;

            case 'paiement':
                // Frais de paiement
                $frais = min($montant * 0.02, 1000); // 2% max 1000 FCFA
                break;

            default:
                $frais = 0;
                break;
        }

        return $frais;
    }

    /**
     * Getter pour accéder au repository depuis le controller
     */
    public function getTransactionsRepo(): TransactionRepository
    {
        return $this->transactionsRepo;
    }

    public function annulerDepot(int $transactionId): bool
    {
        $transaction = $this->transactionsRepo->findById($transactionId);
        if (!$transaction || $transaction['type'] !== 'depot') {
            return false;
        }

        // Mise à jour du solde
        $compte = $this->compteRepository->findById($transaction['compte_id']);
        if (!$compte) return false;

        $nouveauSolde = $compte['solde'] - $transaction['montant'];
        if ($nouveauSolde < 0) return false; // Vérification sécurité

        $this->compteRepository->updateSolde($compte['id'], $nouveauSolde);

        // Annuler le dépôt (supprimer ou marquer comme annulé)
        return $this->transactionsRepo->annulerDepot($transactionId);
    }
}