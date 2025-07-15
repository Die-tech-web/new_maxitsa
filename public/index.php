<?php

use App\Core\Router;
use App\Core\App;
use App\Service\TransactionService;
require_once "../app/config/bootstrap.php";

App::run();


Router::resolve($routes);

$transactionService = new TransactionService();
$last10Transactions = $transactionService->getLast10Transactions(2);




