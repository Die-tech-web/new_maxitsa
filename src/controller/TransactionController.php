<?php
namespace App\Controller;

use App\Core\Abstract\AbstractController;
use App\Service\TransactionService;

class TransactionController extends AbstractController
{
    private TransactionService $transactionService;

    public function __construct()
    {
        parent::__construct();
        $this->baselayout = 'base.layout.html.php';
        $this->transactionService = new TransactionService();

    }
    public function index()
    {
        $user = $this->session->get('user');
        $userId = $user['id'];
        $transactions = $this->transactionService->getLast10Transactions($userId);
        $this->session->set('transactions', $transactions);
        $this->renderHtml("transaction/dashbord", [
            'transactions' => $transactions
        ]);

    }

    public function allTransactions()
    {
        $user = $this->session->get('user');
        if (!$user) {
            http_response_code(403);
            echo json_encode(['error' => 'Non autorisé']);
            exit;
        }

        $userId = $user['id'];
        $transactions = $this->transactionService->getAllTransactions($userId);

        $data = array_map(function ($t) {
            return [
                'date' => $t->getDate()->format('d/m/Y'),
                'montant' => number_format($t->getMontant(), 0, ',', ' '),
                'type' => $t->getTypeTransaction()->value,
            ];
        }, $transactions);

        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }




    public function store()
    {
    }
    public function create()
    {
    }
    public function destroy()
    {
    }
    public function show($id)
    {
    }
    public function edit()
    {
    }
    public function update()
    {
    }
    public function delete()
    {
    }


}