<?php
require __DIR__ . '/../models/Cargo.php';

class DashboardController {
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /auth/login");
            exit();
        }

        $cargoModel = new Cargo();
        $cargos = $cargoModel->getAllCargos($_SESSION['role'], $_SESSION['user_id']);

        require  __DIR__ . '/../views/dashboard.php';
    }
}
?>