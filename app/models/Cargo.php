<?php
require __DIR__ . '/../../config/db.php';

class Cargo {
    public function getAllCargos($role, $user_id) {
        global $pdo;
        if ($role === 'client') {
            $stmt = $pdo->prepare("
                SELECT cargos.*, clients.company_name, CONCAT(managers.first_name, ' ', managers.last_name) AS manager_name 
                FROM cargos 
                JOIN clients ON cargos.client_id = clients.id
                LEFT JOIN managers ON cargos.manager_id = managers.id
                WHERE cargos.client_id = ?
            ");
            $stmt->execute([$user_id]);
        } else { 
            $stmt = $pdo->prepare("
                SELECT cargos.*, clients.company_name, CONCAT(managers.first_name, ' ', managers.last_name) AS manager_name 
                FROM cargos 
                JOIN clients ON cargos.client_id = clients.id
                LEFT JOIN managers ON cargos.manager_id = managers.id
                WHERE cargos.manager_id = ? OR cargos.manager_id IS NULL
            ");
            $stmt->execute([$user_id]);
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllFreeCargos() {
        global $pdo;
    
        $stmt = $pdo->prepare("
            SELECT cargos.*, clients.company_name
            FROM cargos
            JOIN clients ON cargos.client_id = clients.id
            WHERE cargos.manager_id IS NULL
        ");
    
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM cargos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>