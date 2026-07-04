<?php

declare(strict_types=1);

namespace App\Core\Middleware;

use App\Config\App;
use App\Core\JwtService;
use App\Enums\ErrorCode;
use App\Exceptions\AppException;

/**
 * AuthMiddleware – verify access token from HttpOnly cookie or Bearer header.
 *
 * Usage inside a controller:
 *   $payload = AuthMiddleware::handle();
 *   // $payload['sub'] = userId, $payload['email'] = email
 */
final class AuthMiddleware
{
    /**
     * Resolve and verify the access token.
     * Sends a 401 JSON response and exits if the token is missing or invalid.
     *
     * @return array Decoded JWT payload.
     */
    public static function handle(): array
    {
        $token = self::resolveToken();

        if ($token === null) {
            self::abort(AppException::from(ErrorCode::ACCESS_TOKEN_MISSING));
        }

        try {
            $jwt     = new JwtService();
            $payload = $jwt->decode($token, App::jwtSecret());

            if (($payload['type'] ?? '') !== 'access') {
                self::abort(AppException::from(ErrorCode::TOKEN_TYPE_INVALID));
            }

            return $payload;

        } catch (AppException $e) {
            self::abort($e);
        }
    }

    // -----------------------------------------------------------------------
    // Internals
    // -----------------------------------------------------------------------

    private static function resolveToken(): ?string
    {
        // 1. HttpOnly cookie (preferred)
        if (!empty($_COOKIE['access_token'])) {
            return $_COOKIE['access_token'];
        }

        // 2. Fallback: Authorization: Bearer <token> header
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
