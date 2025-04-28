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

        public function index(): void {
            $gyms = [
                1 => 'Липецк, Московская 30',
                2 => 'Липецк, Московская 31',
                3 => 'Липецк, Московская 32',
            ];
            
            $coaches = $this->coachModel->getAll();

            // Рендерит шаблон users.twig и передаёт в него массив с пользователями
            echo $this->twig->render('coaches.twig', ['coaches' => $coaches, 'gyms' => $gyms]);
        }

        // Показ формы добавления тренера
        public function create(): void {
            echo $this->twig->render('add_coach.twig');
        }

        public function delete(): void
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
                $this->coachModel->deleteCoach((int) $_POST['id']);
            }
            header("Location: /coaches");
            exit;
        }

        // Метод для обработки запроса POST /users/add (добавление пользователя)
        public function store(): void {
            // Проверяет, что запрос был отправлен методом POST
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $phoneExists = $this->coachModel->existsByPhone($_POST['phone']);
                $emailExists = $this->coachModel->existsByEmail($_POST['email']);

                if ($phoneExists || $emailExists) {

                    if ($phoneExists) {
                        $errorMessage = 'Номер телефона уже используется';
                    }
                    if ($emailExists) {
                        $errorMessage = 'Email уже используется';
                    }

                    echo "<script>alert('$errorMessage'); window.history.back();</script>";
                    exit;
                }

                // Вызывает метод addUser у модели User, передавая имя, email и возраст пользователя
                $this->coachModel->addCoach($_POST['name'], (int) $_POST['age'], $_POST['gender'], $_POST['phone'], $_POST['email'], $_POST['gym']);
            }
            // Перенаправляет пользователя обратно на страницу /users после добавления
            header("Location: /coaches");
        }
    }
?>
