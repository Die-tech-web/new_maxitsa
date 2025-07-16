<?php
namespace App\Core;
class App
{
    private static $dependencies = [
        
        "router" => \App\Core\Router::class,
        "database" => \App\Core\Database::class,
        "validator" => \App\Core\Validator::class,
        "session" => \App\Core\Session::class,
        "compteController" => \App\Controller\CompteController::class,
        "securityController" => \App\Controller\SecurityController::class,
        "transactionController" => \App\Controller\TransactionController::class,
        "userService" => \App\Service\UserService::class,
        "userRepository" => \App\Repository\UserRepository::class,
        "compteRepository" => \App\Repository\CompteRepository::class,
        "compteService" => \App\Service\CompteService::class,
        "transactionService" => \App\Service\TransactionService::class,
        "transactionRepository" => \App\Repository\TransactionRepository::class,


    ];

    public static function getDependency($key)
    {
       
        if(array_key_exists($key,self::$dependencies)){
           
            $class = self::$dependencies[$key];
            // var_dump($class);
            // die;
            if(class_exists($class) && method_exists($class, 'getInstance')){
                
                return $class::getInstance();
            }
            return new $class();
        }

    }
}