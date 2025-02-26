<?php
require __DIR__ . '/../../config/db.php';

class Cargo {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllCargos($role, $user_id) {
        if ($role === 'client') {
            $stmt = $this->pdo->prepare("
                SELECT cargos.*, clients.company_name, CONCAT(managers.first_name, ' ', managers.last_name) AS manager_name
                FROM cargos
                JOIN clients ON cargos.client_id = clients.id
                LEFT JOIN managers ON cargos.manager_id = managers.id
                WHERE cargos.client_id = ?
            ");
            $stmt->execute([$user_id]);
        } else {
            $stmt = $this->pdo->prepare("
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
        $stmt = $this->pdo->prepare("
            SELECT cargos.*, clients.company_name
            FROM cargos
            JOIN clients ON cargos.client_id = clients.id
            WHERE cargos.manager_id IS NULL
        ");

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM cargos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateCargo($id, $status, $arrival_date) {
        $stmt = $this->pdo->prepare("
            UPDATE cargos SET status = ?, arrival_date = ? WHERE id = ?
        ");
        return $stmt->execute([$status, $arrival_date, $id]);
    }

    public function add($containerNumber, $clientId) {
        $stmt = $this->pdo->prepare("
            INSERT INTO cargos (container, client_id, status) VALUES (?, ?, 'Awaiting')
        ");
        return $stmt->execute([$containerNumber, $clientId]);
    }

    public function assignToManager($cargoId, $managerId) {
        $stmt = $this->pdo->prepare("UPDATE cargos SET manager_id = ? WHERE id = ?");
        return $stmt->execute([$managerId, $cargoId]);
    }

    public function getCargosForExport($userId, $userRole) {
        if ($userRole === 'client') {
            $stmt = $this->pdo->prepare("
                SELECT cargos.id, cargos.container, cargos.status, cargos.arrival_date,
                       clients.company_name AS client_name,
                       CONCAT(managers.first_name, ' ', managers.last_name) AS manager_name
                FROM cargos
                LEFT JOIN clients ON cargos.client_id = clients.id
                LEFT JOIN managers ON cargos.manager_id = managers.id
                WHERE cargos.client_id = ?
            ");
        } elseif ($userRole === 'manager') {
            $stmt = $this->pdo->prepare("
                SELECT cargos.id, cargos.container, cargos.status, cargos.arrival_date,
                       clients.company_name AS client_name,
                       CONCAT(managers.first_name, ' ', managers.last_name) AS manager_name
                FROM cargos
                JOIN clients ON cargos.client_id = clients.id
                LEFT JOIN managers ON cargos.manager_id = managers.id
                WHERE cargos.manager_id = ? OR cargos.manager_id IS NULL
            ");
        } else {
            return [];
        }

        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>