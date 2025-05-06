<?php
namespace App\controllers;

use App\models\User;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class AuthController {
    private User $userModel;
    private Environment $twig;

    public function __construct() {
        $this->userModel = new User();
        $loader = new FilesystemLoader(__DIR__ . '/../views');
        $this->twig = new Environment($loader);
    }

    public function showLoginForm(): void {
        echo $this->twig->render('login.twig');
    }

    public function login(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email']);
            $password = $_POST['password'];

            $user = $this->userModel->getUserByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'] ?? $user['email'];
                $_SESSION['role'] = $user['role'] ?? 'user';
                header("Location: /coaches");
                exit;
            } else {
                echo $this->twig->render('login.twig', [
                    'error' => 'Неверный email или пароль',
                    'email' => htmlspecialchars($email)
                ]);
            }
        }
    }

    public function logout(): void {
        session_destroy();
        header("Location: /coaches");
        exit;
    }

    public function showRegisterForm(): void {
        echo $this->twig->render('register.twig');
    }

    public function register(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            $confirm = $_POST['confirm_password'];

            if ($password !== $confirm) {
                echo $this->twig->render('register.twig', [
                    'error' => 'Пароли не совпадают',
                    'username' => htmlspecialchars($username),
                    'email' => htmlspecialchars($email)
                ]);
                return;
            }

            if ($this->userModel->getUserByEmail($email)) {
                echo $this->twig->render('register.twig', [
                    'error' => 'Email уже зарегистрирован',
                    'username' => htmlspecialchars($username),
                    'email' => htmlspecialchars($email)
                ]);
                return;
            }

            if ($this->userModel->getUserByUsername($username)) {
                echo $this->twig->render('register.twig', [
                    'error' => 'Имя пользователя уже занято',
                    'username' => htmlspecialchars($username),
                    'email' => htmlspecialchars($email)
                ]);
                return;
            }

            $this->userModel->createUser($username, $email, $password);
            header("Location: /login");
            exit;
        }
    }
}
