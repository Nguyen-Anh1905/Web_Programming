<?php

declare(strict_types=1);

namespace App\Config;

use App\Enums\ErrorCode;
use App\Exceptions\AppException;

final class App
{
    public const NAME = 'CRM System';
    public const DEBUG = true;

    // JWT Configuration – secret is read from .env at runtime
    public const ACCESS_TOKEN_TTL  = 15 * 60;       // 15 minutes in seconds
    public const REFRESH_TOKEN_TTL = 7 * 24 * 3600; // 7 days in seconds

    public static function jwtSecret(): string
    {
        $secret = (string) env('JWT_SECRET', '');
        if ($secret === '') {
            throw AppException::from(ErrorCode::JWT_SECRET_NOT_SET);
        }
        return $secret;
    }
}
