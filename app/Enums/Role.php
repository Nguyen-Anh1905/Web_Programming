<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * User roles for RBAC.
 */
enum Role: string
{
    case ADMIN    = 'admin';
    case CUSTOMER = 'customer';

    /** Human-readable label. */
    public function label(): string
    {
        return match($this) {
            self::ADMIN    => 'Quản trị viên',
            self::CUSTOMER => 'Khách hàng',
        };
    }

    /** The dashboard URL path for this role after login. */
    public function dashboardPath(): string
    {
        return match($this) {
            self::ADMIN    => '/admin/dashboard',
            self::CUSTOMER => '/customer/dashboard',
        };
    }

    /** Try to create a Role from a string value, returns null on failure. */
    public static function tryFromString(string $value): ?self
    {
        return self::tryFrom($value);
    }
}
