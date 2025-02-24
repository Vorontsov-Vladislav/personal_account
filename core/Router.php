<?php
class Router {
    public function handle($route) {
        switch ($route) {
            case 'auth/login':
                require __DIR__ . '/../app/controllers/AuthController.php';
                $auth = new AuthController();
                $auth->login();
                break;

            case'auth/authenticate':
                require __DIR__ . '/../app/controllers/AuthController.php';
                $auth = new AuthController();
                $auth->authenticate();
                break;

            default:
                http_response_code(404);
                echo "404 Not Found";
                break;
        }
    }
}