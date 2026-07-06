<?php

declare(strict_types=1);

namespace App\Core;

use App\Exceptions\AppException;

abstract class Controller
{
    protected function view(string $view, array $data = [], ?string $layout = 'main'): void
    {
        View::render($view, $data, $layout);
    }

    protected function redirect(string $url): never
    {
        header('Location: ' . $url);
        exit;
    }

    /**
     * Send a successful JSON response and terminate.
     *
     * @param array<string, mixed> $data  Payload merged after {"success": true}.
     * @param int                  $code  HTTP status code (default 200).
     */
    protected function jsonSuccess(array $data, int $code = 200): never
    {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(array_merge(['success' => true], $data), JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Send an error JSON response and terminate.
     *
     * @param string $message Human-readable error message.
     * @param int    $code    HTTP status code (default 400).
     */
    protected function jsonError(string $message, int $code = 400): never
    {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => false, 'message' => $message], JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Render an AppException directly as a JSON error response and terminate.
     *
     * Usage:
     *   $this->jsonException(AppException::from(ErrorCode::USER_NOT_FOUND));
     */
    protected function jsonException(AppException $e): never
    {
        http_response_code($e->httpCode());
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => false, 'error_code' => $e->errorCode()->value, 'message' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
        exit;
    }
}
