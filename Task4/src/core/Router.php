<?php
    // Объявляет пространство имён для маршрутизатора (Router)
    namespace App\core;

    // Определяет класс Router для управления маршрутами в приложении
    class Router {
        // Приватное свойство для хранения маршрутов (GET и POST)
        private array $routes = [];

        // Метод для регистрации GET-маршрута
        public function get(string $route, callable $callback): void {
            // Добавляет маршрут в массив $routes с ключом 'GET'
            $this->routes['GET'][$route] = $callback;
        }

        // Метод для регистрации POST-маршрута
        public function post(string $route, callable $callback): void {
            // Добавляет маршрут в массив $routes с ключом 'POST'
            $this->routes['POST'][$route] = $callback;
        }

        // Метод для обработки входящего запроса и поиска соответствующего маршрута
        public function resolve(): void {
            // Получает метод HTTP-запроса (GET или POST)
            $method = $_SERVER['REQUEST_METHOD'];

            // Получает путь из URL (например, /users)
            $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            
            // Проверяет, есть ли маршрут для указанного метода и пути
            if (isset($this->routes[$method][$path])) {
                // Вызывает соответствующую функцию-обработчик маршрута
                call_user_func($this->routes[$method][$path]);
            } else {
                // Если маршрут не найден, возвращает 404 Not Found
                http_response_code(404);
                echo "404 Not Found";
            }
        }
    }
?>
