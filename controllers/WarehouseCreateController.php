<?php
require_once "BaseTableTwigController.php";

class WarehouseCreateController extends BaseTableTwigController {
    public $template = "add_warehouse.twig";

    public function get(array $context)
    {        
        parent::get($context);
    }

    public function post(array $context) {
        $warehouse_number = $_POST['warehouse_number'];
        $storekeeper_name = $_POST['storekeeper_name'];
        $password = $_POST['password'];

        $sql = <<<EOL
INSERT INTO Warehouse(Warehouse_number, Full_name_of_storekeepers, password)
VALUES(:warehouse_number, :storekeeper_name, :password)
EOL;


        $query = $this->pdo->prepare($sql);
        $query->bindValue("warehouse_number", $warehouse_number);
        $query->bindValue("storekeeper_name", $storekeeper_name);
        $query->bindValue("password", $password);
        
        $query->execute();

        $this->get($context);
    }
}