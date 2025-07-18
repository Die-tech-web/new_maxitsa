<?php
namespace App\Config;

use App\Core\Middlewares\Auth;

class Middlewares
{
    public static function getMiddlewares(): array
    {
        return [
            'auth' => new Auth(),
        ];
    }
}
