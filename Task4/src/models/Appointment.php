<?php
namespace App\models;

use App\core\Database;
use PDO;

class Appointment {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::connect();
    }

    public function addAppointment(string $clientName, string $phone, int $coachId): void {
        $stmt = $this->pdo->prepare("INSERT INTO appointments (client_name, phone, coach_id) VALUES (:client_name, :phone, :coach_id)");

        $stmt->execute([
            'client_name' => htmlspecialchars($clientName),
            'phone'       => $phone,
            'coach_id'    => $coachId
        ]);
    }

    public function isUserBooked(int $userId, int $coachId): bool {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM appointments WHERE user_id = ? AND coach_id = ?");
        $stmt->execute([$userId, $coachId]);
        return $stmt->fetchColumn() > 0;
    }
    
    public function bookUser(int $userId, int $coachId, string $name, string $phone): void {
        $stmt = $this->pdo->prepare("INSERT INTO appointments (user_id, coach_id, client_name, phone) VALUES (?, ?, ?, ?)");
        $stmt->execute([$userId, $coachId, $name, $phone]);
    }
    
    public function cancelUserBooking(int $userId, int $coachId): void {
        $stmt = $this->pdo->prepare("DELETE FROM appointments WHERE user_id = ? AND coach_id = ?");
        $stmt->execute([$userId, $coachId]);
    }
    
    public function getUserAppointmentCoachId(int $userId): ?int {
        $stmt = $this->pdo->prepare("SELECT coach_id FROM appointments WHERE user_id = ? LIMIT 1");
        $stmt->execute([$userId]);
        $row = $stmt->fetch();
        return $row ? (int)$row['coach_id'] : null;
    }
    
    public function isCoachBooked(int $coachId): bool {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM appointments WHERE coach_id = ?");
        $stmt->execute([$coachId]);
        return $stmt->fetchColumn() > 0;
    }

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

    public function deleteAppointment(int $id): void {
        $stmt = $this->pdo->prepare('DELETE FROM appointments WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}
?>