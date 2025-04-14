<?php
    // Объявляет пространство имён для модели пользователей
    namespace App\models;
    // Подключает класс Database для работы с базой данных
    use App\core\Database;
    // Подключает класс PDO для работы с SQL-запросами
    use PDO;

    // Определяет класс User для работы с таблицей пользователей в базе данных
    class Coach {
        // Приватное свойство для хранения объекта PDO (соединения с базой данных)
        private PDO $pdo;

        // Конструктор класса, вызывается при создании объекта User
        public function __construct() {
            // Получает подключение к базе данных через класс Database (Singleton)
            $this->pdo = Database::connect();
        }

        // Метод для получения всех пользователей из базы данных
        public function getAll(): array {
            // Выполняет SQL-запрос на выборку всех записей из таблицы users
            $stmt = $this->pdo->query("SELECT * FROM coaches");
            // Возвращает массив пользователей в виде ассоциативного массива
            return $stmt->fetchAll();
        }

        // Метод для добавления нового тренера в базу данных
        public function addCoach(string $name, int $age, string $gender, string $phone, string $email, string $gym): void {
            // Подготавливает SQL-запрос с параметрами для вставки данных
            $stmt = $this->pdo->prepare("INSERT INTO coaches (name, age, gender, phone, email, gym) VALUES (:name, :age, :gender, :phone, :email, :gym)");

            // Выполняет запрос с переданными параметрами, предотвращая SQL-инъекции
            $stmt->execute([
                'name'  => htmlspecialchars($name), // Экранирует HTML-теги для защиты от XSS
                'age'   => $age,
                'gender' => $gender,
                'phone' => $phone,
                'email' => filter_var($email, FILTER_SANITIZE_EMAIL), // Очищает email от ненужных символов
                'gym'   => $gym
            ]);
        }
    }
?>
