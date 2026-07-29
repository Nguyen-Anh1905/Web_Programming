<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Middleware\RoleMiddleware;
use App\Enums\Role;
use App\Models\BookingModel;
use App\Models\UserModel;

final class CustomerController extends Controller
{
    public function dashboard(): void
    {
        $payload = RoleMiddleware::handle(Role::CUSTOMER);

        $userId  = (int) ($payload['sub'] ?? 0);
        $profile = (new UserModel())->findById($userId) ?? [];

        $this->view('customer/dashboard', [
            'title'      => 'Customer Dashboard',
            'payload'    => $payload,
            'profile'    => $profile,
            'pageScript' => '/assets/js/customer-dashboard.js',
        ], 'dashboard');
    }

    // -----------------------------------------------------------------------
    // GET /customer/bookings
    // -----------------------------------------------------------------------

    public function getMyBookings(): void
    {
        $payload  = RoleMiddleware::handle(Role::CUSTOMER);
        $userId   = (int) ($payload['sub'] ?? 0);
        $bookings = (new BookingModel())->getByUserId($userId);
        $this->jsonSuccess(['bookings' => $bookings]);
    }
}
