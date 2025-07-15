<?php
// app/core/App.php
namespace App\Core;

use App\Core\Router;
use App\Core\Database;
use App\Core\Validator;
use App\Service\TransactionService;
use App\Service\UserService;

class App
{
    private static array $container = [];
    private static bool $initialized = false;

    public static function run(): void
    {
        self::initialize();
    }

    private static function initialize(): void
    {
        if (self::$initialized) {
            return;
        }

        self::$initialized = true;
    }

    // Méthode pour obtenir le nom de la classe directement
    public static function getDependency(string $key): string
    {
        self::initialize();

        $dependencies = [
            'core' => [],
            'abstract' => [],
            'services' => [
                'userService' => \App\Service\UserService::class,
                'TransactionService' => \App\Service\TransactionService::class,


            ],

            'repositories' => [],
            'controllers' => [
                'securiteController' => \App\Controller\SecurityController::class,
            ],


        ];

        foreach ($dependencies as $category => $services) {
            if (array_key_exists($key, $services)) {
                return $services[$key];
            }
        }

        throw new \Exception("Dependency not found: " . $key);
    }

    // Alias pour getDependency (même fonctionnalité)
    public static function getDependencyClass(string $key)
    {
        return self::getDependency($key);
    }
}