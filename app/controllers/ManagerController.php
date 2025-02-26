<?php
require __DIR__ . '/../models/Manager.php';

class ManagerController {
    private $managerModel;

    public function __construct($pdo) {
        $this->managerModel = new Manager($pdo);
    }

    public function details($id) {
        $manager = $this->managerModel->getById($id);
        if (!$manager) {
            echo "Менеджер не найден.";
            return;
        }

        require __DIR__ . '/../views/manager_details.php';
    }
}

?>