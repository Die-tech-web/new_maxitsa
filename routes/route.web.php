<?php
use App\Controller\SecurityController;
use App\Controller\CompteController;
use App\Controller\TransactionController;
use App\Router;
use App\Core\App;

$routes = [
    '/' => [
        'controller' => SecurityController::class,
        'methode' => 'login'
    ],
    '/auth' => [
        'controller' => SecurityController::class,
        'methode' => 'auth'
    ],
     '/logout' => [
        'controller' => SecurityController::class,
        'methode' => 'login'
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
    ],
    //   '/dashboards' => [
    //     'controller' => App\Controller\DashboardController::class,
    //     'methode' => 'index'
    // ]


    '/test' => [
        'controller' => CompteController::class,
        'methode' => 'test',
    ],



];