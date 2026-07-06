<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Middleware\RoleMiddleware;
use App\Enums\Role;

final class AdminController extends Controller
{
    public function dashboard(): void
    {
        $payload = RoleMiddleware::handle(Role::ADMIN);

        $this->view('admin/dashboard', [
            'title'   => 'Admin Dashboard',
            'payload' => $payload,
        ], 'dashboard');
    }
}
