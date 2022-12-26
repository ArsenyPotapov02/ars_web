<?php
require_once "BaseTableTwigController.php";

class InvoiceCraeteController extends BaseTableTwigController {
    public $template = "add_invoice.twig";

    public function get(array $context)
    {        
        parent::get($context);
    }

    public function post(array $context) {
        $product_name = $_POST['product_name'];
        $count = $_POST['count'];
        $warehouse_number = $_POST['warehouse_number'];

        $sql = <<<EOL
exec [CreateConfir] :product_name, :count
EOL;


        $query = $this->pdo->prepare($sql);
        $query->bindValue("product_name", $product_name);
        $query->bindValue("count", $count);    
       // $query->bindValue("warehouse_number", $warehouse_number);     
        $query->execute();

        $sql = <<<EOL
INSERT INTO Invoice (Warehouse_number, Receipt_date)
VALUES (:warehouse_number, getdate())
EOL;
        $query = $this->pdo->prepare($sql);    
        $query->bindValue("warehouse_number", $warehouse_number);     
        $query->execute();
        $this->get($context);
    }
}