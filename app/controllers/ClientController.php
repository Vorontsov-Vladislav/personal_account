<?php
require __DIR__ . '/../models/Client.php';

class ClientController {
    private $clientModel;

    public function __construct($pdo) {
        $this->clientModel = new Client($pdo);
    }

    public function details($id) {
        $client = $this->clientModel->getById($id);
        if (!$client) {
            echo "Клиент не найден.";
            return;
        }

        require __DIR__ . '/../views/client_details.php';
    }
}

?>