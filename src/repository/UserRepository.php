<?php
namespace App\Repository;
use App\Core\Database;
use PDO;
use PDOException;


class UserRepository
{

    private Database $database ;

    public function __construct()
    {
        $this->database = new Database();
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