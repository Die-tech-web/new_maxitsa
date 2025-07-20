<?php

namespace App\Core;

use Symfony\Component\Yaml\Yaml;

class App
{
    private static array $dependencies = [];

    public static function run(): void
    {
        self::loadServices();
    }

    private static function loadServices(): void
    {
        $path = dirname(__DIR__, 1) . '/config/services.yml';
        if (!file_exists($path)) {
            throw new \Exception("Le fichier services.yml est introuvable.");
        }

        $parsed = Yaml::parseFile($path);

        if (!isset($parsed['services']) || !is_array($parsed['services'])) {
            throw new \Exception("Clé 'services' manquante ou invalide dans services.yml");
        }

        self::$dependencies = $parsed['services'];
    }

    public static function getDependency(string $key): mixed
    {
        if (!isset(self::$dependencies[$key])) {
            throw new \Exception("Dépendance '{$key}' non définie dans services.yml");
        }

        $class = self::$dependencies[$key];

        if (!class_exists($class)) {
            throw new \Exception("Classe {$class} introuvable pour la dépendance '{$key}'");
        }

        if (method_exists($class, 'getInstance')) {
            return $class::getInstance();
        }

        return new $class();
    }
}
