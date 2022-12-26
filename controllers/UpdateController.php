<?php
require_once "BaseTableTwigController.php";

class UpdateController extends BaseTableTwigController {
    public $template = "add_warehouse.twig";

    public function getContext(): array
    {
        $context = parent::getContext();
        $id = isset($_GET['id']) ? $_GET['id'] : '';
        // echo"<pre>";
        // print_r($id);
        // echo"</pre>";
        $table = isset($_GET['table']) ? $_GET['table'] : 'invoice';

        if($table == 'invoice'){
            $this->template = "add_invoice.twig";
            $sql = <<<EOL
SELECT * FROM Invoice_warehouse WHERE Invoice_number = :id
EOL;

            $query = $this->pdo->prepare($sql);
            $query->bindValue("id", $id);     
            $query->execute();
            $context['objects'] = $query->fetch();
 
        }elseif($table == 'warehouse'){
            $this->template = "add_warehouse.twig";
            $sql =<<<EOL
SELECT * FROM Warehouse WHERE warehouse_number = :id
EOL;
            $query = $this->pdo->prepare($sql);
            $query->bindValue("id", $id);     
            $query->execute();
            $context['objects'] = $query->fetch();
            
        }elseif($table == 'product'){
            $this->template = "add_product.twig";
            $sql =<<<EOL
SELECT * FROM Product WHERE Name_of_product = :id
EOL;
            $query = $this->pdo->prepare($sql);
            $query->bindValue("id", $id);     
            $query->execute();
            $context['objects'] = $query->fetch();
        }elseif($table == 'document'){
            header("Location: /?table=document");
            exit;
        }
        // echo"<pre>";
        // print_r($context['objects']);
        // echo"</pre>";

        return $context;
    }

    public function post(array $context)
    {
        $table = isset($_GET['table']) ? $_GET['table'] : 'invoice';
        $product_name = isset($_POST['product_name']) ? $_POST['product_name'] : '';
        $price = isset($_POST['price']) ? $_POST['price'] : '';
        $count = isset($_POST['count']) ? $_POST['count'] : '';
        $id = isset($_GET['id']) ? $_GET['id'] : '';
        $warehouse_number = isset($_POST['warehouse_number']) ? $_POST['warehouse_number'] : '';
        $storekeeper_name = isset($_POST['storekeeper_name']) ? $_POST['storekeeper_name'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';

        // echo"<pre>";
        // print_r($_POST);
        // echo"</pre>";

        if($table == 'invoice'){
            $sql = <<<EOL
UPDATE The_confirmation
SET Quantity_of_goods = :count
Where id=:id 

EOL;

            $query = $this->pdo->prepare($sql);
            $query->bindValue("count", $count);  
            //  $query->bindValue("product_name", $product_name); 
            $query->bindValue("id", $id);    
            $query->execute();

            $sql = <<<EOL
UPDATE Invoice 
set Warehouse_number=:warehouse_number
where Invoice_number = :id

EOL;
            $query = $this->pdo->prepare($sql);    
            $query->bindValue("warehouse_number", $warehouse_number);
            $query->bindValue("id", $id); 
            $query->execute();
        }elseif($table == 'warehouse'){
            $sql =<<<EOL
 UPDATE warehouse
 SET Full_name_of_storekeepers=:storekeeper_name, password=:password
 where Warehouse_number =:warehouse_number
EOL;
            $query = $this->pdo->prepare($sql);
            $query->bindValue("warehouse_number", $warehouse_number);
            $query->bindValue("storekeeper_name", $storekeeper_name);
            $query->bindValue("password", $password);
            $query->execute();
            
        }elseif($table == 'product'){
            $sql =<<<EOL
UPDATE Product
SET Unit_price=:price
where Name_of_product =:product_name
EOL;
            $query = $this->pdo->prepare($sql);
            $query->bindValue("product_name", $product_name);
            $query->bindValue("price", $price);
            $query->execute();
        }
        
        $this->get($context);
    }
}