<?php

declare(strict_types=1);

namespace App\Config;

use App\Core\Router;

final class Routes
{
    public static function register(Router $router): void
    {
        $router->get('/', 'HomeController@index');
    }
}
