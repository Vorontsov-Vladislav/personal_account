<?php
session_start();

require __DIR__ . '/../config/db.php';
require __DIR__ . '/../core/Router.php';
require __DIR__ . '/../app/layouts/header.php';

$router = new Router();

// Регистрируем маршруты здесь, до вызова handle()
$router->get('client/details/{id}', function($id) use ($pdo) {
   require __DIR__ . '/../app/controllers/ClientController.php';
   $controller = new ClientController($pdo);
   $controller->details($id);
});

$router->get('manager/details/{id}', function($id) use ($pdo) {
   require __DIR__ . '/../app/controllers/ManagerController.php';
   $controller = new ManagerController($pdo);
   $controller->details($id);
});

$router->get('cargo/edit/{id}', function($id) use ($pdo) {
   require __DIR__ . '/../app/controllers/CargoController.php';
   $controller = new CargoController($pdo);
   $controller->edit($id);
});

$router->get('cargo/export', function() use ($pdo) {
   require __DIR__ . '/../app/controllers/CargoController.php';
   $controller = new CargoController($pdo);
   $controller->export();
});

$router->get('cargo/export/process', function() use ($pdo) {
   require __DIR__ . '/../app/controllers/CargoController.php';
   $controller = new CargoController($pdo);
   $controller->exportProcess();
});

$router->get('cargo/new', function() use ($pdo) {
   require __DIR__ . '/../app/controllers/CargoController.php';
   $controller = new CargoController($pdo);
   $controller->new();
});

$router->post('/cargo/add', function() use ($pdo) {
   require __DIR__ . '/../app/controllers/CargoController.php';
   $controller = new CargoController($pdo);
   $controller->addCargo();
});

$router->get('cargo/assign-new-cargo', function() use ($pdo) {
   require __DIR__ . '/../app/controllers/CargoController.php';
   $controller = new CargoController($pdo);
   $controller->assignNewCargo();
});

$router->post('/cargo/assign', function() use ($pdo) {
   require __DIR__ . '/../app/controllers/CargoController.php';
   $controller = new CargoController($pdo);
   $controller->assignCargo();
});

$router->get('logout', function() use ($pdo) {
   require __DIR__ . '/../app/controllers/AuthController.php';
   $controller = new AuthController($pdo);
   $controller->logout($id);
});

$router->post('/cargo/update', function() use ($pdo) {
   require __DIR__ . '/../app/controllers/CargoController.php';
   $controller = new CargoController($pdo);
   $controller->update();
});

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = trim($path, '/');

if ($path === '') {
    $path = 'home';
}

$router->handle($path);