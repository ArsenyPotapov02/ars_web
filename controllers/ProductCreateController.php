<?php
require_once "BaseTableTwigController.php";

class ProductCreateController extends BaseTableTwigController {
    public $template = "add_product.twig";

    public function get(array $context)
    {        
        parent::get($context);
    }

    public function post(array $context) {
        $product_name = $_POST['product_name'];
        $price = $_POST['price'];

        $sql = <<<EOL
INSERT INTO Product (Name_of_product, Unit_price)
VALUES(:product_name, :price)
EOL;


        $query = $this->pdo->prepare($sql);
        $query->bindValue("product_name", $product_name);
        $query->bindValue("price", $price);
        
        $query->execute();

        $this->get($context);
    }
}