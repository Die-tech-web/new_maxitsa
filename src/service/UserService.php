<?php
namespace App\Service;

use App\Core\App;
use App\Repository\UserRepository;

class UserService{
    private UserRepository $userRepository;
    public function __construct(){
        $this->userRepository = App::getDependency('userRepository');


    }
    public function getUserByLoginAndPassword(string $login, string $password)
    {
        return $this->userRepository->selectUserByloginAndPassword($login, $password);
    }
    
//     public function getUserByLoginAndPassword($login, $password)
// {
//     $user = $this->userRepository->findBy(['login' => $login]);

//     if ($user && password_verify($password, $user['password'])) {
//         return $user;
//     }

//     return null;
// }

}