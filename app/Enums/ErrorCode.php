<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Centralized error catalogue.
 *
 * Each case carries:
 *   - message()    → human-readable string returned to the client
 *   - httpCode()   → HTTP status code for the response
 */
enum ErrorCode: string
{
    // ── Validation ────────────────────────────────────────────────────────────
    case VALIDATION_FAILED           = 'VALIDATION_FAILED';
    case NAME_REQUIRED               = 'NAME_REQUIRED';
    case EMAIL_INVALID               = 'EMAIL_INVALID';
    case PASSWORD_TOO_SHORT          = 'PASSWORD_TOO_SHORT';
    case EMAIL_OR_PASSWORD_EMPTY     = 'EMAIL_OR_PASSWORD_EMPTY';

    // ── Auth – registration ───────────────────────────────────────────────────
    case EMAIL_ALREADY_EXISTS        = 'EMAIL_ALREADY_EXISTS';

    // ── Auth – login ──────────────────────────────────────────────────────────
    case INVALID_CREDENTIALS         = 'INVALID_CREDENTIALS';

    // ── Auth – tokens ─────────────────────────────────────────────────────────
    case ACCESS_TOKEN_MISSING        = 'ACCESS_TOKEN_MISSING';
    case ACCESS_TOKEN_INVALID        = 'ACCESS_TOKEN_INVALID';
    case ACCESS_TOKEN_EXPIRED        = 'ACCESS_TOKEN_EXPIRED';
    case REFRESH_TOKEN_MISSING       = 'REFRESH_TOKEN_MISSING';
    case REFRESH_TOKEN_INVALID       = 'REFRESH_TOKEN_INVALID';
    case REFRESH_TOKEN_REVOKED       = 'REFRESH_TOKEN_REVOKED';
    case TOKEN_TYPE_INVALID          = 'TOKEN_TYPE_INVALID';

    // ── JWT internals ─────────────────────────────────────────────────────────
    case JWT_FORMAT_INVALID          = 'JWT_FORMAT_INVALID';
    case JWT_SIGNATURE_INVALID       = 'JWT_SIGNATURE_INVALID';
    case JWT_PAYLOAD_INVALID         = 'JWT_PAYLOAD_INVALID';
    case JWT_EXPIRED                 = 'JWT_EXPIRED';
    case JWT_SECRET_NOT_SET          = 'JWT_SECRET_NOT_SET';

    // ── Resources ────────────────────────────────────────────────────────────
    case USER_NOT_FOUND              = 'USER_NOT_FOUND';

    // ── RBAC ──────────────────────────────────────────────────────────────────
    case FORBIDDEN                   = 'FORBIDDEN';
    case ROLE_INVALID                = 'ROLE_INVALID';

    // ── Server ───────────────────────────────────────────────────────────────
    case INTERNAL_ERROR              = 'INTERNAL_ERROR';
    case DB_CONNECTION_FAILED        = 'DB_CONNECTION_FAILED';

    // ─────────────────────────────────────────────────────────────────────────

    /** Human-readable message returned to the client. */
    public function message(): string
    {
        return match($this) {
            // Validation
            self::VALIDATION_FAILED        => 'Dữ liệu không hợp lệ.',
            self::NAME_REQUIRED            => 'Họ tên không được để trống.',
            self::EMAIL_INVALID            => 'Email không hợp lệ.',
            self::PASSWORD_TOO_SHORT       => 'Mật khẩu phải có ít nhất 8 ký tự.',
            self::EMAIL_OR_PASSWORD_EMPTY  => 'Email và mật khẩu không được để trống.',

            // Registration
            self::EMAIL_ALREADY_EXISTS     => 'Email đã được sử dụng.',

            // Login
            self::INVALID_CREDENTIALS      => 'Email hoặc mật khẩu không đúng.',

            // Tokens
            self::ACCESS_TOKEN_MISSING     => 'Access token không tồn tại.',
            self::ACCESS_TOKEN_INVALID     => 'Access token không hợp lệ.',
            self::ACCESS_TOKEN_EXPIRED     => 'Access token đã hết hạn.',
            self::REFRESH_TOKEN_MISSING    => 'Refresh token không tồn tại.',
            self::REFRESH_TOKEN_INVALID    => 'Refresh token không hợp lệ.',
            self::REFRESH_TOKEN_REVOKED    => 'Refresh token đã bị thu hồi.',
            self::TOKEN_TYPE_INVALID       => 'Loại token không hợp lệ.',

            // JWT internals
            self::JWT_FORMAT_INVALID       => 'Định dạng JWT không hợp lệ.',
            self::JWT_SIGNATURE_INVALID    => 'Chữ ký JWT không hợp lệ.',
            self::JWT_PAYLOAD_INVALID      => 'Payload JWT không hợp lệ.',
            self::JWT_EXPIRED              => 'JWT đã hết hạn.',
            self::JWT_SECRET_NOT_SET       => 'JWT_SECRET chưa được cấu hình trong .env.',

            // Resources
            self::USER_NOT_FOUND           => 'Người dùng không tồn tại.',

            // RBAC
            self::FORBIDDEN                => 'Bạn không có quyền truy cập tài nguyên này.',
            self::ROLE_INVALID             => 'Role không hợp lệ.',

            // Server
            self::INTERNAL_ERROR           => 'Lỗi máy chủ nội bộ.',
            self::DB_CONNECTION_FAILED     => 'Không thể kết nối cơ sở dữ liệu.',
        };
    }

    /** HTTP status code associated with this error. */
    public function httpCode(): int
    {
        return match($this) {
            self::VALIDATION_FAILED,
            self::NAME_REQUIRED,
            self::EMAIL_INVALID,
            self::PASSWORD_TOO_SHORT,
            self::EMAIL_OR_PASSWORD_EMPTY   => 422,

            self::EMAIL_ALREADY_EXISTS      => 409,

            self::INVALID_CREDENTIALS,
            self::ACCESS_TOKEN_MISSING,
            self::ACCESS_TOKEN_INVALID,
            self::ACCESS_TOKEN_EXPIRED,
            self::REFRESH_TOKEN_MISSING,
            self::REFRESH_TOKEN_INVALID,
            self::REFRESH_TOKEN_REVOKED,
            self::TOKEN_TYPE_INVALID,
            self::JWT_FORMAT_INVALID,
            self::JWT_SIGNATURE_INVALID,
            self::JWT_PAYLOAD_INVALID,
            self::JWT_EXPIRED               => 401,

            self::USER_NOT_FOUND            => 404,

            self::FORBIDDEN                 => 403,
            self::ROLE_INVALID              => 400,

            self::JWT_SECRET_NOT_SET,
            self::INTERNAL_ERROR,
            self::DB_CONNECTION_FAILED      => 500,
        };
    }
}
