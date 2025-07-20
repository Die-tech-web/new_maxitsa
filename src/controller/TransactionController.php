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

    public function index(): void
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

    public function allTransactions(): void
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

    public function all(): void
    {
        $user = $this->session->get('user');
        if (!$user) {
            http_response_code(403);
            echo json_encode(['error' => 'Non autorisé']);
            exit;
        }

        $userId = $user['id'];
        $page = max(1, (int) ($_GET['page'] ?? 1));
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

    public function create(): void
    {
        $this->renderHtml("transaction/depot");
    }

    public function store(): void
    {
        $user = $this->session->get('user');
        if (!$user) {
            header('Location: /');
            exit;
        }

        $montant = $_POST['montant'] ?? null;
        $typetransaction = $_POST['typetransaction'] ?? null;

        $errors = [];

        if (!$montant || $montant < 100) {
            $errors[] = 'Le montant doit être supérieur ou égal à 100 FCFA.';
        }

        if (!$typetransaction) {
            $errors[] = 'Veuillez choisir un type de transaction.';
        }

        if ($errors) {
            $this->session->set('errors', $errors);
            header('Location: /depot');
            exit;
        }

        $success = $this->transactionService->createDepot($user['id'], (float) $montant, $typetransaction);

        if ($success) {
            $this->session->set('success', 'Dépôt effectué avec succès.');
        } else {
            $this->session->set('errors', ['Une erreur est survenue lors du dépôt.']);
        }

        header('Location: /depot');
        exit;
    }

    // Méthodes placeholders si tu veux les compléter plus tard
    public function destroy(): void {}
    public function show($id): void {}
    public function edit(): void {}
    public function update(): void {}
    public function delete(): void {}
}
