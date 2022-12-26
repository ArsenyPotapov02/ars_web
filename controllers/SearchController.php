<?php
require_once "BaseTableTwigController.php";

class SearchController extends BaseTableTwigController {
    public $template = "main.twig";

    public function getContext(): array
    {
        $context = parent::getContext();
        $table = isset($_SESSION['table']) ? $_SESSION['table'] : '';
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        // $document_number = isset($_SESSION['document_number']) ? $_SESSION['document_number'] : '';
        $warehouse_number = isset($_SESSION['warehouse_number']) ? $_SESSION['warehouse_number'] : '';
        // $invoice_number = isset($_SESSION['invoice_number']) ? $_SESSION['invoice_number'] : '';
// echo "<pre>";
// print_r($_SESSION);
// print_r($table);
// echo "</pre>";

        if($table == 'invoice'){    
            if($_SESSION['role'] == 'admin'){
                $sql = <<<EOL
SELECT * 
FROM Invoice_warehouse
WHERE Invoice_number = :search
EOL;
                $query = $this->pdo->prepare($sql);
                $query->bindValue("search", $search);
            }else{
                $sql = <<<EOL
SELECT * 
FROM Invoice_warehouse
WHERE Warehouse_number = :warehouse_number and Invoice_number = :search
EOL;
                $query = $this->pdo->prepare($sql);
                $query->bindValue("warehouse_number", $warehouse_number);
                $query->bindValue("search", $search);
            }
            

            $context['column_name'] = ['Накладная', 'Название продукта', 'Количество товара', 'Дата получения', 'Номер склада'];
            $context['header'] = 'Таблица накладных';
            $context['title'] = 'Накладная';
        }elseif($table == 'document'){
            if($_SESSION['role'] == 'admin'){
                $sql = <<<EOL
SELECT * 
FROM Document_warehouse
WHERE Document_number = :search
EOL; 
                $query = $this->pdo->prepare($sql);
                $query->bindValue("search", $search);
            }else{
                $sql = <<<EOL
SELECT * 
FROM Document_warehouse
WHERE Warehouse_number = :warehouse_number and Document_number = :search
EOL; 
                $query = $this->pdo->prepare($sql);
                $query->bindValue("warehouse_number", $warehouse_number);
                $query->bindValue("search", $search);
            }
            

            $context['column_name'] = ['Документ', 'Название продукта', 'Количество товара', 'Дата доставки','Номер склада'];
            $context['header'] = 'Таблица документов';
            $context['title'] = 'Документ';
            
        }
               
                
        $query->execute();
        $context['count'] = 4;
        $context['objects'] = $query->fetchAll();
        // echo "<pre>";
        // print_r($context['objects']);
        // print_r($table);
        // echo "</pre>";
        
        return $context;
    }
}