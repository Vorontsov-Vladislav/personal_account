<?php
require __DIR__ . '/../models/Cargo.php';

class DashboardController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /auth/login");
            exit();
        }

        $cargoModel = new Cargo($this->pdo);
        $cargos = $cargoModel->getAllCargos($_SESSION['role'], $_SESSION['user_id']);

        require  __DIR__ . '/../views/dashboard.php';
    }
}
?>