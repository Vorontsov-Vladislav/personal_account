<?php

class AuthController {
    public function login() {
        require __DIR__ . '/../views/auth/login.php';
    }

    public function authenticate() {
        global $pdo;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);

            $stmt = $pdo->prepare("SELECT id, company_name, password, 'client' AS role FROM clients WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                $stmt = $pdo->prepare("SELECT id, CONCAT(first_name, ' ', last_name) AS name, password, 'manager' AS role FROM managers WHERE email = ?");
                $stmt->execute([$email]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
            }

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = isset($user['name']) ? $user['name'] : $user['company_name'];
                $_SESSION['role'] = $user['role'];

                header("Location: /dashboard");
                exit();
            } else {
                $_SESSION['error'] = "Неверный email или пароль!";
                header("Location: /auth/login");
                exit();
            }
        }
    }

    public function logout() {
        session_start();
        session_destroy();
        header("Location: /auth/login");
        exit();
    }
}