<?php
namespace App\Service;

use App\Repository\TransactionRepository;

class TransactionService
{
    private TransactionRepository $transactionsRepo;




    public function __construct()
    {
        $this->transactionsRepo = new TransactionRepository();
    }

    public function getLast10Transactions(int $userId): array
    {

        return $this->transactionsRepo->getLast10Transactions($userId);

    }

    public function getAllTransactions(int $userId): array
    {
        return $this->transactionsRepo->getAllTransactions($userId);
    }


}
