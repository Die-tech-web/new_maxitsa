<?php

namespace App\Core;

use App\Config\Middlewares;
use App\Core\App;

class Router
{
    public static function resolve(array $routes)
    {
        $uri = $_SERVER['REQUEST_URI'];

        if (array_key_exists($uri, $routes)) {
            
            // ✅ CORRECTION: Utiliser $routes[$uri] au lieu de $route
            $route = $routes[$uri];
            
            // Vérifier et exécuter le middleware si nécessaire
            if (isset($route['middleware'])) {
                $middlewares = Middlewares::getMiddlewares();
                if (isset($middlewares[$route['middleware']])) {
                    $middlewares[$route['middleware']]();
                }
            }
            
            // ✅ CORRECTION: Utiliser $route au lieu de $routes[$uri]
            $controllerName = $route['controller'];
            $methode = $route['methode'];
            
            $controller = new $controllerName();
            $controller->$methode();

        } else {
            echo "404 - Page non trouvée";
        }
    }
}