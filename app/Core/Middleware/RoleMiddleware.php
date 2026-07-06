<?php

declare(strict_types=1);

namespace App\Core\Middleware;

use App\Config\App;
use App\Core\JwtService;
use App\Enums\ErrorCode;
use App\Enums\Role;
use App\Exceptions\AppException;

/**
 * RoleMiddleware – verify access token AND check the caller's role.
 *
 * Usage inside a controller:
 *   $payload = RoleMiddleware::handle(Role::ADMIN);
 *   $payload = RoleMiddleware::handle(Role::ADMIN, Role::CUSTOMER); // multiple allowed
 */
final class RoleMiddleware
{
    /**
     * Authenticate the request and assert the caller has at least one of the
     * allowed roles. Terminates with a JSON error if the check fails.
     *
     * @param Role ...$allowed One or more roles that are permitted.
     * @return array Decoded JWT payload.
     */
    public static function handle(Role ...$allowed): array
    {
        // 1. Verify token (reuses AuthMiddleware logic inline to avoid duplication)
        $token = self::resolveToken();

        if ($token === null) {
            self::abort(AppException::from(ErrorCode::ACCESS_TOKEN_MISSING));
        }

        try {
            $jwt     = new JwtService();
            $payload = $jwt->decode($token, App::jwtSecret());
        } catch (AppException $e) {
            self::abort($e);
        }

        if (($payload['type'] ?? '') !== 'access') {
            self::abort(AppException::from(ErrorCode::TOKEN_TYPE_INVALID));
        }

        // 2. Check role
        $roleValue = $payload['role'] ?? '';
        $role      = Role::tryFrom($roleValue);

        if ($role === null) {
            self::abort(AppException::from(ErrorCode::ROLE_INVALID));
        }

        if (!in_array($role, $allowed, true)) {
            self::abort(AppException::from(ErrorCode::FORBIDDEN));
        }

        return $payload;
    }

    // -----------------------------------------------------------------------
    // Internals
    // -----------------------------------------------------------------------

    private static function resolveToken(): ?string
    {
        if (!empty($_COOKIE['access_token'])) {
            return $_COOKIE['access_token'];
        }

        $header = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        if (str_starts_with($header, 'Bearer ')) {
            return substr($header, 7);
        }

        return null;
    }

    /** @return never */
    private static function abort(AppException $e): never
    {
        http_response_code($e->httpCode());
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success'    => false,
            'error_code' => $e->errorCode()->value,
            'message'    => $e->getMessage(),
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
}
