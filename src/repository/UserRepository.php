<?php
namespace App\Repository;
use App\Core\Database;
use PDO;
use PDOException;
use App\Core\Abstract\AbstractRepository;


class UserRepository extends AbstractRepository
{

    public function __construct()
    {
        parent::__construct();
    }

    public function selectUserByloginAndPassword(string $login, string $password)
    {
        $sql = "SELECT * FROM users WHERE login = :login AND password = :password";
        $stmt = $this->database->getPdo()->prepare($sql);
        $stmt->execute([
            'login' => $login,
            'password' => $password
        ]);

        return $result = $stmt->fetch(PDO::FETCH_ASSOC);

    }
}