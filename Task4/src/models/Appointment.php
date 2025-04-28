<?php
// Объявляет пространство имён для модели записей
namespace App\models;

// Подключает класс Database для работы с базой данных
use App\core\Database;
use PDO;

// Определяет класс Appointment для работы с таблицей записей
class Appointment {
    // Приватное свойство для хранения объекта PDO (соединения с базой данных)
    private PDO $pdo;

    // Конструктор класса, вызывается при создании объекта Appointment
    public function __construct() {
        // Получает подключение к базе данных через класс Database
        $this->pdo = Database::connect();
    }

    // Метод для добавления новой записи к тренеру
    public function addAppointment(string $clientName, string $phone, int $coachId): void {
        // Подготавливает SQL-запрос с параметрами для вставки данных
        $stmt = $this->pdo->prepare("INSERT INTO appointments (client_name, phone, coach_id) VALUES (:client_name, :phone, :coach_id)");

        // Выполняет запрос с переданными параметрами, предотвращая SQL-инъекции
        $stmt->execute([
            'client_name' => htmlspecialchars($clientName),
            'phone'       => $phone,
            'coach_id'    => $coachId
        ]);
    }

    // Метод для получения всех записей
    public function getAll(): array {
        $stmt = $this->pdo->query("
            SELECT 
                appointments.id, 
                appointments.client_name, 
                appointments.phone, 
                appointments.coach_id, 
                coaches.name AS coach_name
            FROM appointments
            JOIN coaches ON appointments.coach_id = coaches.id
        ");
        return $stmt->fetchAll();
    }

    // Метод для удаления записи
    public function deleteAppointment(int $id): void {
        $stmt = $this->pdo->prepare('DELETE FROM appointments WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}
?>