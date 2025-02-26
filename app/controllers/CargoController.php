<?php
require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../models/Cargo.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class CargoController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function export() {
        require __DIR__ . '/../views/cargo/export_form.php';
    }

    public function new() {
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'manager') {
            echo 'У пользователя с данной ролью нет доступа к данному функционалу. <a href="/dashboard">Назад</a>';
            die;
        }

        require __DIR__ . '/../views/cargo/add_cargo.php';
    }

    public function edit($id) {
        $cargoModel = new Cargo();
        $cargo = $cargoModel->getById($id);
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'client') {
            echo 'У пользователя с данной ролью нет доступа к данному функционалу. <a href="/dashboard">Назад</a>';
            die;
        }

        if (!$cargo) {
            echo "Контейнер не найден.";
            return;
        }

        require __DIR__ . '/../views/cargo/edit_cargo.php';
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die ('Недопустимый метод');
        }

        $cargoModel = new Cargo();
        $id = $_POST['id'];
        $status = $_POST['status'];
        $arrival_date = !empty($_POST['arrival_date']) ? $_POST['arrival_date'] . ' 00:00:00' : NULL;
        $cargo = $cargoModel->getById($id);

        if (!$cargo) {
            die ('Груз не найден'); 
        }

        $stmt = $this->pdo->prepare("UPDATE cargos SET status = ?, arrival_date = ? WHERE id = ?");
        $stmt->execute([$status, $arrival_date, $id]);

        header("Location: /dashboard");
        exit;
    }

    public function assignNewCargo() {
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'client') {
            echo 'У пользователя с данной ролью нет доступа к данному функционалу. <a href="/dashboard">Назад</a>';
            die;
        }

        $cargoModel = new Cargo();
        $cargos = $cargoModel->getAllFreeCargos();

        require __DIR__ . '/../views/cargo/assign_new_cargo_form.php';
    }

    public function addCargo() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $containerNumber = $_POST['container'];
            $clientId = $_SESSION['user_id'];
            $stmt = $this->pdo->prepare("INSERT INTO cargos (container, client_id, status) VALUES (?, ?, 'Awaiting')");
            $stmt->execute([$containerNumber, $clientId]);
    
            header("Location: /dashboard");
            exit;
        }
    }

    public function assignCargo() {
        if ($_SESSION['role'] !== 'manager') {
            echo "Доступ запрещен!";
            exit;
        }
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cargoId = $_POST['cargo_id'];
            $managerId = $_SESSION['user_id'];
    
            $stmt = $this->pdo->prepare("UPDATE cargos SET manager_id = ? WHERE id = ?");
            $stmt->execute([$managerId, $cargoId]);
    
            header("Location: /cargo/assign-new-cargo");
            exit;
        }
    }

    public function exportProcess() {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || !isset($_POST['export_type'])) {
            die("Ошибка доступа!");
        }

        $exportType = $_POST['export_type'];
        $userEmail = $_SESSION['email'];

        $filePath = $this->generateExcelFile();

        if ($exportType === 'download') {
            $this->downloadExcelFile($filePath);
        } elseif ($exportType === 'email') {
            $filePath = $this->generateExcelFile();
            if ($this->sendEmailWithAttachment($userEmail, $filePath)) {
                echo "Файл успешно отправлен на email: $userEmail";
            } else {
                echo "Ошибка при отправке email.";
            }
        } else {
            echo "Некорректный выбор.";
        }
    }

    private function generateExcelFile() {
        $filePath = __DIR__ . '/../../exports/cargos' . time() . '.xlsx';
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $userId = $_SESSION['user_id'];
        $userRole = $_SESSION['role'];

        if ($userRole === 'client') {
            $stmt = $this->pdo->prepare("
                SELECT cargos.id, cargos.container, cargos.status, cargos.arrival_date,
                       clients.company_name AS client_name,
                       CONCAT(managers.first_name, ' ', managers.last_name) AS manager_name
                FROM cargos
                LEFT JOIN clients ON cargos.client_id = clients.id
                LEFT JOIN managers ON cargos.manager_id = managers.id
                WHERE cargos.client_id = ?
            ");
            $stmt->execute([$userId]);
        } elseif ($userRole === 'manager') {
            $stmt = $this->pdo->prepare("
                SELECT cargos.id, cargos.container, cargos.status, cargos.arrival_date,
                       clients.company_name AS client_name,
                       CONCAT(managers.first_name, ' ', managers.last_name) AS manager_name
                FROM cargos
                JOIN clients ON cargos.client_id = clients.id
                LEFT JOIN managers ON cargos.manager_id = managers.id
                WHERE cargos.manager_id = ? OR cargos.manager_id IS NULL
            ");
            $stmt->execute([$userId]);
        } else {
            die("Недостаточно прав для экспорта данных.");
        }

        $cargos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Контейнер');
        $sheet->setCellValue('C1', 'Статус');
        $sheet->setCellValue('D1', 'Клиент');
        $sheet->setCellValue('E1', 'Менеджер');
        $sheet->setCellValue('F1', 'Дата прибытия');

        $row = 2;
        foreach ($cargos as $cargo) {
            $sheet->setCellValue('A' . $row, $cargo['id']);
            $sheet->setCellValue('B' . $row, $cargo['container']);
            $sheet->setCellValue('C' . $row, $cargo['status']);
            $sheet->setCellValue('D' . $row, $cargo['client_name']);
            $sheet->setCellValue('E' . $row, $cargo['manager_name']);
            $sheet->setCellValue('F' . $row, $cargo['arrival_date']);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);

        return $filePath;
    }

    private function downloadExcelFile($filePath) {
        if (file_exists($filePath)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="cargos.xlsx"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filePath));
            readfile($filePath);
            exit;
        } else {
            echo "Ошибка: файл не найден!";
        }
    }

    private function sendEmailWithAttachment($toEmail, $filePath) {
        $mailDir = __DIR__ . '/../../mail/';

        if (!is_dir($mailDir)) {
            mkdir($mailDir, 0777, true);
        }

        $emailFile = $mailDir . 'email_' . time() . '.eml';

        $message = "Здравствуйте,\n\nВам отправлено письмо с экспортом ваших грузов.\n\n";
        $boundary = uniqid("PHP-mixed-");

        $headers = "From: noreply@example.com\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";

        $emailBody = "--$boundary\r\n";
        $emailBody .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $emailBody .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $emailBody .= $message . "\r\n\r\n";

        if (file_exists($filePath)) {
            $fileContent = chunk_split(base64_encode(file_get_contents($filePath)));
            $fileName = basename($filePath);
            $emailBody .= "--$boundary\r\n";
            $emailBody .= "Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; name=\"$fileName\"\r\n";
            $emailBody .= "Content-Disposition: attachment; filename=\"$fileName\"\r\n";
            $emailBody .= "Content-Transfer-Encoding: base64\r\n\r\n";
            $emailBody .= $fileContent . "\r\n\r\n";
        }

        $emailBody .= "--$boundary--";

        file_put_contents($emailFile, "To: $toEmail\r\n" . $headers . "\r\n" . $emailBody);
        return true;
    }
}