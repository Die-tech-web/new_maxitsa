<?php
namespace App\Controller;

use App\Core\Abstract\AbstractController;
use App\Core\App;
use App\Service\TransactionService;

class TransactionController extends AbstractController
{
    private TransactionService $transactionService;

    public function __construct()
    {
        parent::__construct();
        $this->baselayout = 'base.layout.html.php';
        $this->transactionService = App::getDependency('transactionService');

    }
    public function index()
    {
       
        $user = $this->session->get('user');
        if (!$user) {
            header("Location: /");
            exit;
        }
        
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
    // TransactionController.php
    public function all()
    {
        $user = $this->session->get('user');
        $userId = $user['id'];

        $page = $_GET['page'] ?? 1;
        $page = max(1, (int) $page);
        $limit = 10;

        $repo = App::getDependency('transactionRepository');
        $paginator = App::getDependency('paginationService');

        $total = $repo->countTransactions($userId);
        $pagination = $paginator->getPagination($total, $page, $limit);
        $transactions = $repo->getPaginatedTransactions($userId, $pagination['limit'], $pagination['offset']);

        
        $result = array_map(fn($t) => [
            'date' => $t->getDate()->format('d/m/Y'),
            'montant' => number_format($t->getMontant(), 0, ',', ' '),
            'type' => $t->getTypeTransaction()->value
        ], $transactions);

        echo json_encode([
            'transactions' => $result,
            'pagination' => $pagination
        ]);
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