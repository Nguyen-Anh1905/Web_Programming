<?php

declare(strict_types=1);

namespace App\Core;

use App\Config\Routes;

final class Application
{
    private Router $router;

    public function __construct()
    {
        $this->router = new Router();
        Routes::register($this->router);
    }

    public function run(): void
    {
        $this->router->dispatch();
    }
}
