<?php

declare(strict_types=1);

namespace App\Config;

/**
 * Database configuration – values are read from the .env file via env().
 * Edit .env (never .env.example) to set your real credentials.
 */
final class Database
{
    public static function host(): string     { return (string) env('DB_HOST', '127.0.0.1'); }
    public static function port(): string     { return (string) env('DB_PORT', '3306'); }
    public static function name(): string     { return (string) env('DB_NAME', 'crm_system'); }
    public static function user(): string     { return (string) env('DB_USER', 'root'); }
    public static function password(): string { return (string) env('DB_PASSWORD', ''); }
    public static function charset(): string  { return (string) env('DB_CHARSET', 'utf8mb4'); }
}
