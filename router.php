<?php
$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

if (file_exists(__DIR__ . $path) && !is_dir(__DIR__ . $path)) {
    return false;
}

require __DIR__ . '/public/index.php';