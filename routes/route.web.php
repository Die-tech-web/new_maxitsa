<?php
use App\Controller\SecurityController;
use App\Controller\CompteController;
use App\Controller\TransactionController;



return $routes = [

    '/' => [
        'controller' => SecurityController::class,
        'methode' => 'login',
    ],
    '/auth' => [
        'controller' => SecurityController::class,
        'methode' => 'auth',
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




];