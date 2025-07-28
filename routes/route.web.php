<?php
use App\Controller\SecurityController;
use App\Controller\CompteController;
use App\Controller\TransactionController;
use App\Controller\WoyofalController; // Ajout du contrôleur Woyofal

return $routes = [
    '/' => [
        'controller' => SecurityController::class,
        'methode' => 'login',
    ],
    '/auth' => [
        'controller' => SecurityController::class,
        'methode' => 'auth',
    ],
    '/inscription' => [
        'controller' => SecurityController::class,
        'methode' => 'register',
    ],
    '/register' => [
        'controller' => SecurityController::class,
        'methode' => 'handleRegister',
    ],
    '/logout' => [
        'controller' => SecurityController::class,
        'methode' => 'logout',
        'middleware' => 'auth',
    ],
    '/dashboard' => [
        'controller' => CompteController::class,
        'methode' => 'index',
        'middleware' => 'auth',
    ],
    '/compte/list' => [
        'controller' => CompteController::class,
        'methode' => 'listComptes',
        'middleware' => 'auth',
    ],
    '/dashbord' => [
        'controller' => TransactionController::class,
        'methode' => 'index',
        'middleware' => 'auth',
    ],
    '/transactions/all' => [
        'controller' => TransactionController::class,
        'methode' => 'allTransactions',
        'middleware' => 'auth',
    ],
    '/compte/ajouter-secondaire' => [
        'controller' => CompteController::class,
        'methode' => 'ajouterCompteSecondaire',
        'middleware' => 'auth',
    ],
    '/compte/basculer-principal' => [
        'controller' => CompteController::class,
        'methode' => 'changerComptePrincipal',
        'middleware' => 'auth',
    ],
    '/depot' => [
        'controller' => TransactionController::class,
        'methode' => 'create',
        'middleware' => 'auth',
    ],
    '/transaction/store' => [
        'controller' => TransactionController::class,
        'methode' => 'store',
        'middleware' => 'auth',
    ],
    '/transactions/annuler' => [
        'controller' => TransactionController::class,
        'methode' => 'annulerDepot',
        'middleware' => 'auth',
    ],

    // ========== ROUTES WOYOFAL ==========
    '/woyofal/acheter' => [
        'controller' => WoyofalController::class,
        'methode' => 'afficherFormulaire',
        'middleware' => 'auth',
    ],
    '/woyofal/payer' => [
        'controller' => WoyofalController::class,
        'methode' => 'traiterPaiement',
        'middleware' => 'auth',
    ],
    '/woyofal/confirmation' => [
        'controller' => WoyofalController::class,
        'methode' => 'afficherConfirmation',
        'middleware' => 'auth',
    ],
    '/unauthorized' => [
        'controller' => SecurityController::class,
        'methode' => 'unauthorized',
        'middleware' => 'auth',
    ],
];