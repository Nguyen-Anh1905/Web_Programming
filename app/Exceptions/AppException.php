<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Enums\ErrorCode;
use RuntimeException;

/**
 * Application-level exception that carries a typed ErrorCode.
 *
 * Usage:
 *   throw new AppException(ErrorCode::INVALID_CREDENTIALS);
 *   throw new AppException(ErrorCode::VALIDATION_FAILED, 'Custom override message');
 *
 * The exception exposes:
 *   - errorCode()  → the ErrorCode enum case
 *   - httpCode()   → HTTP status int derived from the enum (unless overridden)
 *   - getMessage() → human-readable string (from enum, or custom override)
 */
final class AppException extends RuntimeException
{
    private ErrorCode $errorCode;
    private int $httpStatus;

    public function __construct(
        ErrorCode $errorCode,
        ?string   $messageOverride = null,
        ?int      $httpCodeOverride = null,
    ) {
        $this->errorCode  = $errorCode;
        $this->httpStatus = $httpCodeOverride ?? $errorCode->httpCode();

        parent::__construct($messageOverride ?? $errorCode->message());
    }

    /** The typed error code enum case. */
    public function errorCode(): ErrorCode
    {
        return $this->errorCode;
    }

    /** HTTP status code to use in the response. */
    public function httpCode(): int
    {
        return $this->httpStatus;
    }

    /**
     * Convenience static factory – reads nicer than `new AppException(...)`.
     *
     *   AppException::from(ErrorCode::USER_NOT_FOUND)
     */
    public static function from(
        ErrorCode $errorCode,
        ?string   $messageOverride = null,
        ?int      $httpCodeOverride = null,
    ): self {
        return new self($errorCode, $messageOverride, $httpCodeOverride);
    }
}
