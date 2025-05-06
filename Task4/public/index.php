<?php
session_start();

require __DIR__ . '/../vendor/autoload.php';
use App\core\Router;

use App\controllers\CoachController;
use App\controllers\AppointmentController;
use App\controllers\AuthController;

$router = new Router();

$coaches = new CoachController();
$router->get('/coaches', [$coaches, 'index']);
$router->get('/coaches/add', [$coaches, 'create']);
$router->post('/coaches/delete', [$coaches, 'delete']);
$router->post('/coaches/add', [$coaches, 'store']);
$router->post('/coaches/report', [$coaches, 'report']);

$appointmentController = new AppointmentController();
$router->get('/appointments/add', [$appointmentController, 'create']);
$router->post('/appointments/add', [$appointmentController, 'store']);
$router->get('/appointments', [$appointmentController, 'index']);
$router->post('/appointments/delete', [$appointmentController, 'delete']);
$router->post('/appointments/toggle', [$appointmentController, 'toggle']);

$auth = new AuthController();
$router->get('/login', [$auth, 'showLoginForm']);
$router->post('/login', [$auth, 'login']);
$router->get('/logout', [$auth, 'logout']);
$router->get('/register', [$auth, 'showRegisterForm']);
$router->post('/register', [$auth, 'register']);

$router->resolve();
?>
