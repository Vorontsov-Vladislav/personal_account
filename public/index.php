<?php
session_start();

require __DIR__ . '/../config/db.php';
require __DIR__ . '/../core/Router.php';

$router = new Router();

$path =  parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = trim($path, '/');

if ($path === '') {
   $path = 'home'; 
}

$router->handle($path);