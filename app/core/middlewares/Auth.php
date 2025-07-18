<?php
namespace App\Core\Middlewares;

use App\Core\App;

class Auth
{
    public function __invoke()
    {
         $session = App::getDependency('session');

        if (!$_SESSION['user'] ?? null) {
            header('Location: /');
            exit;
        }

        return true;
    }
}

