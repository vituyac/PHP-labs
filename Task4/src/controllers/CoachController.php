<?php
// Объявляет пространство имён для контроллера пользователей
namespace App\controllers;

// Подключает модель User для работы с базой данных
use App\models\Coach;

// Подключает класс Twig Environment (шаблонизатор)
use Twig\Environment;

// Подключает загрузчик файловой системы Twig (для работы с шаблонами)
use Twig\Loader\FilesystemLoader;

// Определяет класс контроллера пользователей
class CoachController {
    // Приватное свойство для работы с моделью пользователей (экземпляр User)
    private Coach $coachModel;

    // Приватное свойство для работы с шаблонизатором Twig
    private Environment $twig;

    // Конструктор класса, вызывается при создании объекта UserController
    public function __construct() {
        // Создаёт объект модели User для взаимодействия с базой данных
        $this->coachModel = new Coach();

        // Создаёт загрузчик шаблонов Twig, указывая путь к папке views
        $loader = new FilesystemLoader(__DIR__ . '/../views');

        // Создаёт объект Twig для рендеринга шаблонов
        $this->twig = new Environment($loader);
    }

    // Метод для обработки запроса GET /users
    public function index(): void {
        // Получает список всех пользователей из базы данных
        $coaches = $this->coachModel->getAll();

        // Рендерит шаблон users.twig и передаёт в него массив с пользователями
        echo $this->twig->render('coaches.twig', ['coaches' => $coaches]);
    }

    // Метод для обработки запроса POST /users/add (добавление пользователя)
    public function store(): void {
        // Проверяет, что запрос был отправлен методом POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Вызывает метод addUser у модели User, передавая имя, email и возраст пользователя
            $this->coachModel->addCoach($_POST['name'], (int) $_POST['age'], $_POST['gender'], $_POST['phone'], $_POST['email'], $_POST['gym']);
        }
        // Перенаправляет пользователя обратно на страницу /users после добавления
        header("Location: /coaches");
    }
}
?>
