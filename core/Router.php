<?php
    // var_dump($_SERVER['REQUEST_METHOD']);exit;
class Router {
    private $routes = [];

    public function get($route, $callback) {
        $this->routes['GET'][$route] = $callback;
    }

    public function post($route, $callback) {
        $this->routes['POST'][$route] = $callback;
    }

    public function handle($path) {
        // Проверяем маршруты со статическими путями (switch)
        switch ($path) {
            case 'auth/login':
                require __DIR__ . '/../app/controllers/AuthController.php';
                $auth = new AuthController();
                $auth->login();
                return;

            case 'auth/authenticate':
                require __DIR__ . '/../app/controllers/AuthController.php';
                $auth = new AuthController();
                $auth->authenticate();
                return;

            case 'dashboard':
                require __DIR__ . '/../app/controllers/DashboardController.php';
                $dashboard = new DashboardController();
                $dashboard->index();
                return;
        }

        $method = $_SERVER['REQUEST_URI'];
        foreach ($this->routes['GET'] as $route => $callback) {
            $pattern = preg_replace('#\{([^}]+)\}#', '([^/]+)', $route);
            if (preg_match("#^$pattern$#", $path, $matches)) {
                array_shift($matches);
                call_user_func_array($callback, $matches);
                return;
            }
        }

        if (isset($this->routes['POST'])) {
            call_user_func($this->routes["POST"][$method]);
            return;
        }

        http_response_code(404);
        echo "404 Not Found";
    }
}