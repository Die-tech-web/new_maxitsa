<?php

// $dotenv = Dotenv\Dotenv::createImmutable('../');
// $dotenv->load();

// define("URL", $_ENV["URL"]);
// define("DB_USERNAME", $_ENV["DB_USERNAME"]);
// define("DB_PASSWORD", $_ENV["DB_PASSWORD"]);
// define( "DSN", $_ENV["DSN"]);



$dotenv = Dotenv\Dotenv::createImmutable('../');
$dotenv->load();

define("URL", $_ENV["URL"] ?? '');
define("DB_USERNAME", $_ENV["DB_USERNAME"] ?? '');
define("DB_PASSWORD", $_ENV["DB_PASSWORD"] ?? '');
define("DSN", $_ENV["DSN"] ?? '');
