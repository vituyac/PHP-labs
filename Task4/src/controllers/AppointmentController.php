<?php
namespace App\controllers;

use App\models\Appointment;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class AppointmentController
{
    private Environment $twig;
    private Appointment $appointmentModel;

    public function __construct()
    {
        $loader = new FilesystemLoader(__DIR__ . '/../views');
        $this->twig = new Environment($loader);
        $this->appointmentModel = new Appointment();
    }

    // Показать форму записи
    public function create(): void
    {
        $coachId = $_GET['coach_id'] ?? null;
        echo $this->twig->render('appointment_form.twig', ['coach_id' => $coachId]);
    }

    // Сохранить запись после отправки формы
    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $clientName = $_POST['name'];
            $phone = $_POST['phone'];
            $coachId = (int) $_POST['coach_id'];

            $this->appointmentModel->addAppointment($clientName, $phone, $coachId);

            header('Location: /coaches'); // После успешной записи вернуться к списку тренеров
            exit;
        }
    }

    // Показать список всех записей
    public function index(): void
    {
        $appointments = $this->appointmentModel->getAll();
        echo $this->twig->render('appointments.twig', ['appointments' => $appointments]);
    }

    // Удаление записи
    public function delete(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $this->appointmentModel->deleteAppointment((int) $_POST['id']);
        }
        header('Location: /appointments');
        exit;
    }
}
?>