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

            // Préparer les données de transaction
            $transaction = [
                'compte_id' => $compte['id'],
                'montant' => $montant,
                'type' => $type,
                'date' => date('Y-m-d H:i:s'),
            ];

            // Nouveau solde après déduction
            $compte['solde'] -= $total;

            // Enregistrer la transaction et mettre à jour le solde
            return $this->transactionsRepo->storeDepot($transaction, $compte);

        } catch (\Exception $e) {
            error_log("Erreur dans createDepot: " . $e->getMessage());
            Session::getInstance()->set('errors', ['Une erreur est survenue lors du traitement.']);
            return false;
        }
    }
}