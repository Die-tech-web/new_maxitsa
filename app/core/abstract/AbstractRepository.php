<?php

namespace App\Core\Abstract;
use App\Core\Database;

use PDO;
class AbstractRepository
{
    protected Database $database;

    public function __construct()
    {
        $this->database = new Database();
    }

    // protected function getPdo(): ?PDO
    // {
    //     return $this->database->getPdo();
    // }
}
