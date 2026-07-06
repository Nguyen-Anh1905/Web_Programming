<?php

declare(strict_types=1);

namespace App\Config;

use App\Core\Router;

final class Routes
{
    public static function register(Router $router): void
    {
        // Home
        $router->get('/', 'HomeController@index');

        // Auth – pages
        $router->get('/auth/login',    'AuthController@showLogin');
        $router->get('/auth/register', 'AuthController@showRegister');

        // Auth – API endpoints
        $router->post('/auth/login',    'AuthController@login');
        $router->post('/auth/register', 'AuthController@register');
        $router->post('/auth/logout',   'AuthController@logout');
        $router->post('/auth/refresh',  'AuthController@refresh');

        // Admin dashboard
        $router->get('/admin/dashboard', 'AdminController@dashboard');

        // Customer dashboard
        $router->get('/customer/dashboard', 'CustomerController@dashboard');
    }
}
