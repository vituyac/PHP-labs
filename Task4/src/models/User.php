<?php
    namespace App\models;
    use App\core\Database;
    use PDO;

    class User {
        private PDO $pdo;

        public function __construct() {
            $this->pdo = Database::connect();
        }

        public function getUserByEmail(string $email): ?array {
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            return $stmt->fetch() ?: null;
        }

        public function createUser(string $username, string $email, string $password): void {
            $stmt = $this->pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$username, $email, password_hash($password, PASSWORD_DEFAULT)]);
        }

        public function getUserByUsername(string $username): ?array {
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        }
    }
?>
