<?php

declare(strict_types=1);

namespace App\Core;

use App\Config\App;
use App\Enums\ErrorCode;
use App\Exceptions\AppException;

/**
 * Pure-PHP JWT implementation using HMAC-SHA512 (HS512).
 * No external library required.
 */
final class JwtService
{
    private const HASH_ALGO  = 'sha512';
    private const HEADER_B64 = 'eyJhbGciOiJIUzUxMiIsInR5cCI6IkpXVCJ9'; // {"alg":"HS512","typ":"JWT"}

    // -----------------------------------------------------------------------
    // Public API
    // -----------------------------------------------------------------------

    /**
     * Create a signed JWT token from a payload array.
     */
    public function encode(array $payload, string $secret): string
    {
        $headerEncoded  = self::HEADER_B64;
        $payloadEncoded = $this->base64UrlEncode((string) json_encode($payload, JSON_THROW_ON_ERROR));
        $signingInput   = $headerEncoded . '.' . $payloadEncoded;
        $signature      = $this->sign($signingInput, $secret);

        return $signingInput . '.' . $signature;
    }

    /**
     * Verify and decode a JWT token.
     *
     * @throws AppException on invalid token, bad signature, or expiry.
     */
    public function decode(string $token, string $secret): array
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            throw AppException::from(ErrorCode::JWT_FORMAT_INVALID);
        }

        [$headerEncoded, $payloadEncoded, $signatureProvided] = $parts;

        // Verify signature
        $signingInput      = $headerEncoded . '.' . $payloadEncoded;
        $expectedSignature = $this->sign($signingInput, $secret);

        if (!hash_equals($expectedSignature, $signatureProvided)) {
            throw AppException::from(ErrorCode::JWT_SIGNATURE_INVALID);
        }

        // Decode payload
        $payload = json_decode(
            $this->base64UrlDecode($payloadEncoded),
            true,
            512,
            JSON_THROW_ON_ERROR,
        );

        if (!is_array($payload)) {
            throw AppException::from(ErrorCode::JWT_PAYLOAD_INVALID);
        }

        // Check expiry
        if (isset($payload['exp']) && time() > (int) $payload['exp']) {
            throw AppException::from(ErrorCode::JWT_EXPIRED);
        }

        return $payload;
    }

    // -----------------------------------------------------------------------
    // Token factories
    // -----------------------------------------------------------------------

    /**
     * Generate a short-lived access token (15 min by default).
     */
    public function generateAccessToken(int $userId, string $email): string
    {
        $payload = [
            'iss'   => 'crm-system',
            'sub'   => $userId,
            'email' => $email,
            'type'  => 'access',
            'iat'   => time(),
            'exp'   => time() + App::ACCESS_TOKEN_TTL,
        ];

        return $this->encode($payload, App::jwtSecret());
    }

    /**
     * Generate a long-lived refresh token (7 days by default).
     */
    public function generateRefreshToken(int $userId): string
    {
        $payload = [
            'iss'  => 'crm-system',
            'sub'  => $userId,
            'type' => 'refresh',
            'jti'  => bin2hex(random_bytes(32)), // unique token ID
            'iat'  => time(),
            'exp'  => time() + App::REFRESH_TOKEN_TTL,
        ];

        return $this->encode($payload, App::jwtSecret());
    }

    // -----------------------------------------------------------------------
    // Internals
    // -----------------------------------------------------------------------

    private function sign(string $data, string $secret): string
    {
        return $this->base64UrlEncode(
            hash_hmac(self::HASH_ALGO, $data, $secret, true),
        );
    }

    private function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private function base64UrlDecode(string $data): string
    {
        $remainder = strlen($data) % 4;
        if ($remainder !== 0) {
            $data .= str_repeat('=', 4 - $remainder);
        }

        $decoded = base64_decode(strtr($data, '-_', '+/'), true);
        if ($decoded === false) {
            throw AppException::from(ErrorCode::JWT_PAYLOAD_INVALID);
        }

        return $decoded;
    }
}
