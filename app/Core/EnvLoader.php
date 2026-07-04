<?php

declare(strict_types=1);

namespace App\Core;

use RuntimeException;

/**
 * Minimal .env file parser – no external library required.
 *
 * Supports:
 *   KEY=value
 *   KEY="quoted value"
 *   KEY='quoted value'
 *   # comments
 *   blank lines
 *
 * Call EnvLoader::load() once at bootstrap (public/index.php).
 * Then read values with env('KEY') or env('KEY', 'default').
 */
final class EnvLoader
{
    private static bool $loaded = false;

    /**
     * Parse and load a .env file into $_ENV and putenv().
     *
     * @param string $path Absolute path to the .env file.
     */
    public static function load(string $path): void
    {
        if (self::$loaded) {
            return;
        }

        if (!is_file($path)) {
            throw new RuntimeException(".env file not found at: {$path}");
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        if ($lines === false) {
            throw new RuntimeException("Could not read .env file at: {$path}");
        }

        foreach ($lines as $line) {
            $line = trim($line);

            // Skip comments and empty lines
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            // Must contain '='
            if (!str_contains($line, '=')) {
                continue;
            }

            [$key, $value] = explode('=', $line, 2);

            $key   = trim($key);
            $value = trim($value);

            // Strip surrounding quotes
            if (
                strlen($value) >= 2
                && (
                    (str_starts_with($value, '"') && str_ends_with($value, '"'))
                    || (str_starts_with($value, "'") && str_ends_with($value, "'"))
                )
            ) {
                $value = substr($value, 1, -1);
            }

            // Skip if already set in the environment (allows OS env to override)
            if (isset($_ENV[$key]) || getenv($key) !== false) {
                continue;
            }

            $_ENV[$key] = $value;
            putenv("{$key}={$value}");
        }

        self::$loaded = true;
    }
}
