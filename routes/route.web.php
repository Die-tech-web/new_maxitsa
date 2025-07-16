<?php
use App\Controller\SecurityController;
use App\Controller\CompteController;
use App\Controller\TransactionController;
use App\Router;
use App\Core\App;

$routes = [
    '/' => [
        'controller' => App::getDependency('securiteController'),
        'methode' => 'login'
    ],
    '/auth' => [
        'controller' => SecurityController::class,
        'methode' => 'auth'
    ],
    '/dashboard' => [
        'controller' => CompteController::class,
        'methode' => 'index',
    ],
    '/dashbord' => [
        'controller' => TransactionController::class,
        'methode' => 'index',
    ],
    '/transactions/all' => [
        'controller' => TransactionController::class,
        'methode' => 'allTransactions',
    ],
    '/transactions/paginate' => [
        'controller' => TransactionController::class,
        'methode' => 'all',
    ]




];