<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Config\App;
use App\Core\Controller;

final class HomeController extends Controller
{
    public function index(): void
    {
        $this->view('home/index', [
            'title' => App::NAME,
            'message' => 'MVC skeleton is ready. Branch feature/project-skeleton completed.',
        ]);
    }
}
