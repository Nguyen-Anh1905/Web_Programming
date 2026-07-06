<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Middleware\RoleMiddleware;
use App\Enums\Role;

final class CustomerController extends Controller
{
    public function dashboard(): void
    {
        $payload = RoleMiddleware::handle(Role::CUSTOMER);

        $this->view('customer/dashboard', [
            'title'   => 'Customer Dashboard',
            'payload' => $payload,
        ], 'dashboard');
    }
}
