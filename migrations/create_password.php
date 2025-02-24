<?php
require 'config/db.php';

$newPassword = '123456';
$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

try {
    $stmt = $pdo->prepare("UPDATE clients SET password = ?");
    $stmt->execute([$hashedPassword]);
    echo "Пароли в таблице clients успешно обновлены.";

    $stmt = $pdo->prepare("UPDATE managers SET password = ?");
    $stmt->execute([$hashedPassword]);
    echo "Пароли в таблице managers успешно обновлены.";
} catch (PDOException $e) {
    echo "Ошибка обновления паролей: " . $e->getMessage();
}
?>