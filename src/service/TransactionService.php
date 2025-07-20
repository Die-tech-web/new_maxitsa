<?php
namespace App\Service;

use App\Core\App;
use App\Repository\TransactionRepository;

class TransactionService
{
    private TransactionRepository $transactionsRepo;


    public function __construct()
    {
        $this->transactionsRepo = App::getDependency('transactionRepository');
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
    return $this->transactionsRepo->createDepot($userId, $montant, $type);
}




}