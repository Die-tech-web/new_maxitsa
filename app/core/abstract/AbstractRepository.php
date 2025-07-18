<?php

namespace App\Core\Abstract;
use App\Core\App;
use App\Core\Database;

use PDO;
class AbstractRepository
{
    protected Database $database;

    public function __construct()
    {
        $this->database = App::getDependency('database');
    }

    // protected function getPdo(): ?PDO
    // {
    //     return $this->database->getPdo();
    // }
}
