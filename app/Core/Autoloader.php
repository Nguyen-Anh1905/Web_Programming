<?php

declare(strict_types=1);

namespace App\Core;

final class Autoloader
{
    private const NAMESPACE_PREFIX = 'App\\';

    public static function register(): void
    {
        spl_autoload_register([self::class, 'load']);
    }

    public static function load(string $class): void
    {
        if (!str_starts_with($class, self::NAMESPACE_PREFIX)) {
            return;
        }

        $relativeClass = substr($class, strlen(self::NAMESPACE_PREFIX));
        $file = APP_PATH . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $relativeClass) . '.php';

        if (is_file($file)) {
            require_once $file;
        }
    }
}
