<?php
namespace App\controllers;

use App\models\Appointment;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use App\helpers\Auth;

class AppointmentController {
    private Environment $twig;
    private Appointment $appointmentModel;

    public function __construct() {
        $loader = new FilesystemLoader(__DIR__ . '/../views');
        $this->twig = new Environment($loader);
        $this->appointmentModel = new Appointment();
    }

    public function index(): void {
        if (!Auth::isAdmin()) {
            header("Location: /coaches");
            exit;
        }

        $appointments = $this->appointmentModel->getAll();
        echo $this->twig->render('appointments.twig', [
            'appointments' => $appointments,
            'username' => Auth::getUsername()
        ]);
    }

    public function toggle(): void {
        if (!Auth::isLoggedIn()) {
            header("Location: /login");
            exit;
        }
    
        $userId = $_SESSION['user_id'];
        $coachId = (int) ($_POST['coach_id'] ?? 0);
        $username = $_SESSION['username'] ?? 'Пользователь';
        $phone = '—';
    
        $currentCoachId = $this->appointmentModel->getUserAppointmentCoachId($userId);
        $isCoachBooked = $this->appointmentModel->isCoachBooked($coachId);
    
        if ($currentCoachId === $coachId) {
            $this->appointmentModel->cancelUserBooking($userId, $coachId);
        }
        elseif ($currentCoachId === null && $isCoachBooked) {
            header("Location: /coaches");
            exit;
        }
        elseif ($currentCoachId === null && !$isCoachBooked) {
            $this->appointmentModel->bookUser($userId, $coachId, $username, $phone);
        }
    
        header("Location: /coaches");
        exit;
    }

    public function delete(): void {
        if (!Auth::isAdmin()) {
            header("Location: /coaches");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $this->appointmentModel->deleteAppointment((int) $_POST['id']);
        }

        header('Location: /appointments');
        exit;
    }
}