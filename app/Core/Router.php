<?php

declare(strict_types=1);

namespace App\Core;

final class Router
{
    /** @var array<int, array{method: string, path: string, handler: string}> */
    private array $routes = [];

    public function get(string $path, string $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, string $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }

    public function dispatch(): void
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $requestUri = $this->resolveRequestUri();

        foreach ($this->routes as $route) {
            if ($route['method'] !== $requestMethod) {
                continue;
            }

            $pattern = $this->compilePathToRegex($route['path']);
            if (!preg_match($pattern, $requestUri, $matches)) {
                continue;
            }

            array_shift($matches);
            $this->invokeHandler($route['handler'], $matches);
            return;
        }

        $this->renderNotFound();
    }

    private function addRoute(string $method, string $path, string $handler): void
    {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $this->normalizePath($path),
            'handler' => $handler,
        ];
    }

    private function resolveRequestUri(): string
    {
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
        $basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');

        if ($basePath !== '' && $basePath !== '/' && str_starts_with($uri, $basePath)) {
            $uri = substr($uri, strlen($basePath)) ?: '/';
        }

        return $this->normalizePath($uri);
    }

    private function normalizePath(string $path): string
    {
        $path = '/' . trim($path, '/');

        return $path === '//' ? '/' : $path;
    }

    private function compilePathToRegex(string $path): string
    {
        $pattern = preg_replace('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', '([^/]+)', $path) ?? $path;

        if ($pattern === '/') {
            return '#^/$#';
        }

        return '#^' . rtrim($pattern, '/') . '$#';
    }

    private function invokeHandler(string $handler, array $params = []): void
    {
        if (!str_contains($handler, '@')) {
            $this->renderNotFound();
            return;
        }

        [$controllerName, $action] = explode('@', $handler, 2);
        $controllerClass = 'App\\Controllers\\' . $controllerName;

        if (!class_exists($controllerClass)) {
            $this->renderNotFound();
            return;
        }

        $controller = new $controllerClass();

        if (!method_exists($controller, $action)) {
            $this->renderNotFound();
            return;
        }

        call_user_func_array([$controller, $action], $params);
    }

    private function renderNotFound(): void
    {
        http_response_code(404);
        View::render('errors/404', [], null);
    }
}
