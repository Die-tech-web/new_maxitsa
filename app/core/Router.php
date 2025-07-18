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
           
            if (isset($route['middleware'])) {
                $middlewares = Middlewares::getMiddlewares();
                if (isset($middlewares[$routes['middleware']])) {
                    $middlewares[$routes['middleware']]();
                }
            }
        $controllerName = $routes[$uri]['controller'];
        $methode = $routes[$uri]['methode'];
        $controller = new $controllerName();
        $controller->$methode();


        } else {
            echo "404";
        }

    }
}
