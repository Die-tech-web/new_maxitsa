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
        $this->compteRepository = App::getDependency('compteRepository');
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
        $user = $this->session->get('user');
        if (!$user) {
            header("Location: /");
            exit;
        }

        // Récupérer tous les comptes de l'utilisateur via le service
        try {
            $compteService = App::getDependency('compteService');
            $comptes = $compteService->getAllComptesByUserId($user['id']);
            $this->session->set('comptes_user', $comptes);
        } catch (\Exception $e) {
            // Si erreur, on continue sans les comptes (fallback)
            error_log("Erreur récupération comptes: " . $e->getMessage());
            $this->session->set('comptes_user', []);
        }

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
        $compteSourceTel = $_POST['compte_source'] ?? null; // Maintenant c'est le numéro de téléphone

        $errors = [];

        if (!$montant || $montant < 100) {
            $errors[] = 'Le montant doit être supérieur ou égal à 100 FCFA.';
        }

        if (!$typetransaction) {
            $errors[] = 'Veuillez choisir un type de transaction.';
        }

        // Si un compte source est sélectionné, utiliser la nouvelle logique
        if ($compteSourceTel) {
            // NOUVELLE LOGIQUE AVEC SÉLECTION DE COMPTE
            $compteService = App::getDependency('compteService');
            $compteSource = null;
            
            // Récupérer tous les comptes pour trouver celui sélectionné par numéro de téléphone
            $tousLesComptes = $compteService->getAllComptesByUserId($user['id']);
            foreach ($tousLesComptes as $compte) {
                if ($compte['numerotel'] == $compteSourceTel) {
                    $compteSource = $compte;
                    break;
                }
            }

            if (!$compteSource) {
                $errors[] = 'Compte source non trouvé.';
            } else {
                // Calculer les frais selon le type de compte
                $frais = 0;
                if ($typetransaction === 'depot') {
                    if ($compteSource['typecompte'] === 'principal') {
                        $frais = $montant * 0.0085; // 0,85% pour compte principal
                    }
                    // Pas de frais pour compte secondaire
                } elseif ($typetransaction === 'depot_principal_to_principal') {
                    $frais = min($montant * 0.08, 5000);
                } elseif ($typetransaction === 'retrait') {
                    $frais = $montant * 0.01; // 1% de frais de retrait
                } elseif ($typetransaction === 'paiement') {
                    $frais = min($montant * 0.02, 1000); // 2% max 1000 FCFA
                }

                $total = $montant + $frais;

                // Vérifier le solde seulement pour les retraits/paiements, pas pour les dépôts
                if (in_array($typetransaction, ['retrait', 'paiement', 'depot_principal_to_principal'])) {
                    if ($compteSource['solde'] < $total) {
                        $errors[] = "Solde insuffisant. Besoin: " . number_format($total, 0, ',', ' ') . " FCFA, Disponible: " . number_format($compteSource['solde'], 0, ',', ' ') . " FCFA";
                    }
                }
            }

            if ($errors) {
                $this->session->set('errors', $errors);
                header('Location: /depot');
                exit;
            }

            // Effectuer la transaction avec le compte sélectionné
            $transactionData = [
                'compte_id' => $compteSource['id'],
                'montant' => $montant,
                'type' => $typetransaction,
                'date' => date('Y-m-d H:i:s'),
            ];

            // CORRECTION : Gérer correctement les dépôts vs retraits
            if ($typetransaction === 'depot') {
                // Pour un dépôt, on AJOUTE le montant au solde du compte sélectionné
                $compteSource['solde'] = $compteSource['solde'] + $montant;
                
                // Si c'est un compte secondaire, débiter le compte principal
                if ($compteSource['typecompte'] === 'secondaire') {
                    $compteRepo = App::getDependency('compteRepository');
                    $comptePrincipal = $compteRepo->getComptePrincipal($user['id']);
                    
                    if ($comptePrincipal) {
                        // Vérifier si le compte principal a assez de fonds
                        if ($comptePrincipal['solde'] < $montant) {
                            $this->session->set('errors', ['Solde insuffisant dans le compte principal pour effectuer ce dépôt.']);
                            header('Location: /depot');
                            exit;
                        }
                        
                        // Débiter le compte principal
                        $nouveauSoldePrincipal = $comptePrincipal['solde'] - $montant;
                        $compteRepo->updateSolde($comptePrincipal['id'], $nouveauSoldePrincipal);
                    }
                }
            } else {
                // Pour retrait, paiement, etc., on soustrait le total (montant + frais)
                $compteSource['solde'] = $compteSource['solde'] - $total;
            }

            $success = $this->transactionService->getTransactionsRepo()->storeDepot($transactionData, $compteSource);

        } else {
            // VOTRE CODE EXISTANT (INCHANGÉ)
            $compteRepo = App::getDependency('compteRepository');
            $comptePrincipal = $compteRepo->getComptePrincipal($user['id']);

            if (!$comptePrincipal) {
                $errors[] = "Aucun compte principal trouvé pour l'utilisateur.";
            }

            $isPrincipal = $comptePrincipal['typecompte'] === 'principal';
            $soldeComptePrincipal = $comptePrincipal['solde'];

            $frais = 0;
            if ($isPrincipal && $typetransaction === 'depot') {
                $frais = $montant * 0.0085;
                $total = $montant + $frais;

                if ($soldeComptePrincipal < $total) {
                    $errors[] = "Solde insuffisant pour couvrir le dépôt et les frais (égal à $total FCFA).";
                }
            }

            if ($typetransaction === 'depot_principal_to_principal') {
                $frais = min($montant * 0.08, 5000);
                $total = $montant + $frais;

                if ($soldeComptePrincipal < $total) {
                    $errors[] = "Solde insuffisant pour le transfert entre comptes principaux avec frais.";
                }
            }

            if ($errors) {
                $this->session->set('errors', $errors);
                header('Location: /depot');
                exit;
            }

            $success = $this->transactionService->createDepot($user['id'], (float) $montant, $typetransaction);
        }

        // Gestion du résultat (commun aux deux logiques)
        if ($success) {
            $this->session->set('success', 'Transaction effectuée avec succès.');
        } else {
            $this->session->set('errors', ['Une erreur est survenue lors de la transaction.']);
        }

        header('Location: /depot');
        exit;
    }

    public function annulerDepot(): void
    {
        $this->session->set('errors', ['Dépôt annulé.']);
        header('Location: /dashboard');
        exit;
    }

    // Méthodes placeholders si tu veux les compléter plus tard
    public function destroy(): void {}
    public function show($id): void {}
    public function edit(): void {}
    public function update(): void {}
    public function delete(): void {}
}