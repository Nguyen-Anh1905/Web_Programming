<?php

declare(strict_types=1);

if (!function_exists('env')) {
    /**
     * Get an environment variable with an optional default value.
     *
     * @param  string $key     The variable name.
     * @param  mixed  $default Returned when the variable is not set.
     * @return mixed
     */
    function env(string $key, mixed $default = null): mixed
    {
        $value = $_ENV[$key] ?? getenv($key);

        if ($value === false || $value === null) {
            return $default;
        }

        // Cast common string representations to proper types
        return match (strtolower((string) $value)) {
            'true',  '(true)'  => true,
            'false', '(false)' => false,
            'null',  '(null)'  => null,
            'empty', '(empty)' => '',
            default            => $value,
        };
    }
}
