<?php

class RowDeleteController extends BaseController {
    public function post(array $context)
    {
        $id = isset($_POST['id']) ? $_POST['id'] : '';
        $table = isset($_SESSION['table']) ? $_SESSION['table'] : 'invoice';
        // echo "<pre>";
        // print_r($table);
        // print_r($id);
        // echo "</pre>";
        if($table == 'invoice'){
            $sql =<<<EOL
DELETE FROM Invoice WHERE Invoice_number = :id
EOL;
        }elseif($table == 'warehouse'){
            $sql =<<<EOL
EXEC Delete_warehouse :id
EOL;
        }elseif($table == 'document'){
            $sql =<<<EOL
DELETE FROM Shipping_document WHERE Document_number = :id
EOL;
        }else{
            $sql =<<<EOL
exec Delete_product2 :id
EOL;
        }
        $query = $this->pdo->prepare($sql);
        $query->bindValue(":id", $id);
        $query->execute();
        header("Location: /");
        exit;
        $this->get($context);
    }

}