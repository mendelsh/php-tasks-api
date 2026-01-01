<?php
declare(strict_types=1);

namespace App;

class Router {
    private array $routes = [];

    public function get(string $path, array $handler): void {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, array $handler): void {
        $this->routes['POST'][$path] = $handler;
    }

    public function put(string $path, array $handler): void {
        $this->routes['PUT'][$path] = $handler;
    }

    public function patch(string $path, array $handler): void {
        $this->routes['PATCH'][$path] = $handler;
    }

    public function delete(string $path, array $handler): void {
        $this->routes['DELETE'][$path] = $handler;
    }

    public function dispatch(string $uri, string $method): void {
        $path = parse_url($uri, PHP_URL_PATH);

        // Try exact match first
        if (isset($this->routes[$method][$path])) {
            [$class, $function] = $this->routes[$method][$path];
            $controller = new $class();
            $controller->$function();
            return;
        }

        // Try to match dynamic routes (e.g., /tasks/123)
        foreach ($this->routes[$method] ?? [] as $route => $handler) {
            // Replace :id with regex pattern
            $pattern = preg_replace('#/:id#', '/(\d+)', $route);
            // Escape forward slashes and other special chars, but keep the regex group
            $pattern = '#^' . str_replace('/', '\/', $pattern) . '$#';
            
            if (preg_match($pattern, $path, $matches)) {
                [$class, $function] = $handler;
                $controller = new $class();
                $id = (int)$matches[1];
                $controller->$function($id);
                return;
            }
        }

        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Route not found']);
    }
}