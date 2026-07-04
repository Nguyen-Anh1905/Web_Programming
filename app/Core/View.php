<?php

declare(strict_types=1);

namespace App\Core;

final class View
{
    public static function render(string $view, array $data = [], ?string $layout = 'main'): void
    {
        $viewFile = self::resolveViewPath($view);

        if (!is_file($viewFile)) {
            http_response_code(500);
            echo 'View not found.';
            return;
        }

        extract($data, EXTR_SKIP);

        if ($layout === null) {
            require $viewFile;
            return;
        }

        $layoutFile = APP_PATH . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . $layout . '.php';

        if (!is_file($layoutFile)) {
            http_response_code(500);
            echo 'Layout not found.';
            return;
        }

        ob_start();
        require $viewFile;
        $content = ob_get_clean() ?: '';

        require $layoutFile;
    }

    private static function resolveViewPath(string $view): string
    {
        $view = str_replace(['..', "\0"], '', $view);
        $segments = explode('/', $view);

        return APP_PATH . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $segments) . '.php';
    }
}
