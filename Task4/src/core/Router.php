<?php

namespace App\core;

class Router {
    private array $routes = [];

    public function get(string $route, $callback): void {
        $this->routes['GET'][$route] = $callback;
    }

    public function post(string $route, $callback): void {
        $this->routes['POST'][$route] = $callback;
    }

    public function resolve(): void {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $callback = $this->routes[$method][$path] ?? null;

        if (!$callback) {
            http_response_code(404);
            echo "404 Not Found";
            return;
        }

        if (is_callable($callback)) {
            call_user_func($callback);
        } elseif (is_array($callback) && count($callback) === 2) {
            [$controller, $method] = $callback;
            if (is_object($controller) && method_exists($controller, $method)) {
                call_user_func([$controller, $method]);
            } else {
                http_response_code(500);
                echo "500 Internal Server Error (Invalid controller method)";
            }
        } else {
            http_response_code(500);
            echo "500 Internal Server Error (Invalid route callback)";
        }
    }
}
