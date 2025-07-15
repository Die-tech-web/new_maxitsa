<?php

namespace App\Core;
class Router
{


    public static function resolve(array $routes)
    {

        $uri = $_SERVER['REQUEST_URI'];
        
        if (array_key_exists($uri, $routes)) {
            $controllerName = $routes[$uri]['controller'];
            $methode = $routes[$uri]['methode'];
            $controller = new $controllerName();
            $controller->$methode();

        } else {
            echo "404";
        }

    }
}