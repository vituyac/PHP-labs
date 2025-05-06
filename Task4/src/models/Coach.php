<?php
    namespace App\models;
    use App\core\Database;
    use PDO;

    class Coach {
        private PDO $pdo;

        public function __construct() {
            $this->pdo = Database::connect();
        }

        public function getAll(): array {
            $stmt = $this->pdo->query("SELECT * FROM coaches");
            return $stmt->fetchAll();
        }

        public function addCoach(string $name, int $age, string $gender, string $phone, string $email, string $gym): void {
            $stmt = $this->pdo->prepare("INSERT INTO coaches (name, age, gender, phone, email, gym) VALUES (:name, :age, :gender, :phone, :email, :gym)");

            $stmt->execute([
                'name'  => htmlspecialchars($name),
                'age'   => $age,
                'gender' => $gender,
                'phone' => $phone,
                'email' => filter_var($email, FILTER_SANITIZE_EMAIL),
                'gym'   => $gym
            ]);
        }

        public function deleteCoach(int $id): void
        {
            $stmt = $this->pdo->prepare('DELETE FROM coaches WHERE id = :id');
            $stmt->execute(['id' => $id]);
        }

        public function existsByPhone(string $phone): bool
        {
            $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM coaches WHERE phone = :phone');
            $stmt->execute(['phone' => $phone]);
            return $stmt->fetchColumn() > 0;
        }

        public function existsByEmail(string $email): bool
        {
            $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM coaches WHERE email = :email');
            $stmt->execute(['email' => $email]);
            return $stmt->fetchColumn() > 0;
        }
    }
?>
