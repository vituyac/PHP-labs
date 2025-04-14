<?php
// Подключает автозагрузку Composer, чтобы автоматически загружать классы
require __DIR__ . '/../vendor/autoload.php';

// Подключает класс Router для обработки маршрутов
use App\core\Router;

// Подключает контроллер пользователей, который содержит методы для работы с пользователями
use App\controllers\CoachController;

// Создаёт экземпляр класса Router для управления маршрутизацией
$router = new Router();

// Создаёт экземпляр контроллера UserController для обработки запросов, связанных с пользователями
$coaches = new CoachController();

// Определяет маршрут для обработки GET-запроса к /users
// Когда пользователь переходит по /users, вызывается метод index() у UserController
$router->get('/coaches', [$coaches, 'index']);

// Определяет маршрут для обработки POST-запроса к /users/add
// Когда пользователь отправляет POST-запрос на /users/add, вызывается метод store() у UserController
$router->post('/coaches/add', [$coaches, 'store']);

// Запускает механизм маршрутизации
// Определяет, какой маршрут был запрошен, и вызывает соответствующий метод контроллера
$router->resolve();
?>
