<?php
namespace App\controllers;
use App\models\Coach;
use App\models\Appointment;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use App\helpers\Auth;

require __DIR__ . '/../../vendor/autoload.php';
use tFPDF;

class CoachController {
    private Coach $coachModel;
    private Environment $twig;

    public function __construct() {
        $this->coachModel = new Coach();
        $loader = new FilesystemLoader(__DIR__ . '/../views');
        $this->twig = new Environment($loader);
    }

    public function index(): void {
        $gyms = [
            1 => 'Липецк, Московская 30',
            2 => 'Липецк, Московская 31',
            3 => 'Липецк, Московская 32',
        ];
    
        $coaches = $this->coachModel->getAll();
    
        $is_logged_in = Auth::isLoggedIn();
        $is_admin = Auth::isAdmin();
        $username = Auth::getUsername();
        $user_id = $_SESSION['user_id'] ?? null;
    
        $booked_coach_id = null;
        $occupied_coach_ids = [];
    
        if ($is_logged_in && $user_id) {
            $appointmentModel = new \App\models\Appointment();
    
            $booked_coach_id = $appointmentModel->getUserAppointmentCoachId($user_id);
            foreach ($coaches as $coach) {
                if ($appointmentModel->isCoachBooked($coach['id'])) {
                    $occupied_coach_ids[] = $coach['id'];
                }
            }
        }
    
        echo $this->twig->render('coaches.twig', [
            'coaches' => $coaches,
            'gyms' => $gyms,
            'is_admin' => $is_admin,
            'is_logged_in' => $is_logged_in,
            'username' => $username,
            'booked_coach_id' => $booked_coach_id,
            'occupied_coach_ids' => $occupied_coach_ids
        ]);
    }
    

    public function create(): void {
        if (!Auth::isAdmin()) {
            header("Location: /coaches");
            exit;
        }

        echo $this->twig->render('add_coach.twig', [
            'username' => Auth::getUsername()
        ]);
    }

    public function delete(): void {
        if (!Auth::isAdmin()) {
            header("Location: /coaches");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $this->coachModel->deleteCoach((int) $_POST['id']);
        }

        header("Location: /coaches");
        exit;
    }

    public function store(): void {
        if (!Auth::isAdmin()) {
            header("Location: /coaches");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $phoneExists = $this->coachModel->existsByPhone($_POST['phone']);
            $emailExists = $this->coachModel->existsByEmail($_POST['email']);

            if ($phoneExists || $emailExists) {
                $errorMessage = $phoneExists ? 'Номер телефона уже используется' : 'Email уже используется';
                echo "<script>alert('$errorMessage'); window.history.back();</script>";
                exit;
            }

            $this->coachModel->addCoach(
                $_POST['name'],
                (int) $_POST['age'],
                $_POST['gender'],
                $_POST['phone'],
                $_POST['email'],
                $_POST['gym']
            );
        }

        header("Location: /coaches");
    }

    public function report(): void {
        if (!\App\helpers\Auth::isAdmin()) {
            header("Location: /coaches");
            exit;
        }
    
        $coaches = $this->coachModel->getAll();
    
        $encodeText = function (string $text): string {
            return iconv('UTF-8', 'CP1251//IGNORE', $text);
        };
    
        $pdf = new tFPDF();
        $pdf->AddPage();
        
        $pdf->AddFont('DejaVu', '', 'DejaVuSans.ttf', true);
        $pdf->SetFont('DejaVu', '', 10);
    
        $pdf->Cell(10, 10, $encodeText('ID'), 1);
        $pdf->Cell(35, 10, $encodeText('Name'), 1);
        $pdf->Cell(15, 10, $encodeText('Age'), 1);
        $pdf->Cell(20, 10, $encodeText('Gender'), 1);
        $pdf->Cell(30, 10, $encodeText('Phone'), 1);
        $pdf->Cell(50, 10, $encodeText('Email'), 1);
        $pdf->Cell(30, 10, $encodeText('Gym'), 1);
        $pdf->Ln();
    
        foreach ($coaches as $coach) {
            $pdf->Cell(10, 10, $coach['id'], 1);
            $pdf->Cell(35, 10, $encodeText($coach['name']), 1);
            $pdf->Cell(15, 10, $encodeText($coach['age']), 1);
            $pdf->Cell(20, 10, $encodeText($coach['gender']), 1);
            $pdf->Cell(30, 10, $encodeText($coach['phone']), 1);
            $pdf->Cell(50, 10, $encodeText($coach['email']), 1);
            $pdf->Cell(30, 10, $encodeText($coach['gym']), 1);
            $pdf->Ln();
        }
    
        $pdf->Output('D', 'coaches_report.pdf');
        exit;
    }
    
}
