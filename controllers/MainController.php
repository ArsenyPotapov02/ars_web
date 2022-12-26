<?php
require_once "BaseTableTwigController.php";

class MainController extends BaseTableTwigController {
    public $template = "main.twig";

    public function getContext(): array
    {
        $context = parent::getContext();  
        $table = isset($_GET['table']) ? $_GET['table'] : (isset($_SESSION['table']) ? $_SESSION['table'] : 'invoice');
        $cure_page = isset($_GET['page']) ? $_GET['page'] : '';
        $warehouse_number = isset($_SESSION['warehouse_number']) ? $_SESSION['warehouse_number'] : '';
        $limit = 100;
        $start = 0;
        $_SESSION['table'] = $table;
        
        $role = isset($_SESSION['role']) ? $_SESSION['role'] : 'admin';
        $context['role'] = $role;
        // echo "<pre>";
        // print_r($table);
        // echo "</pre>";
        $context['table'] = $table;
        
        if($cure_page != ''){
            $start = $limit*$cure_page-$limit; 
        } 
        
        if($table == 'invoice'){

            if($role == 'admin'){
                $sql = <<<EOL
SELECT count(*) FROM Invoice_warehouse
EOL;
                $query = $this->pdo->query($sql);
                $count_all = $query->fetch();
                $sql = <<<EOL
SELECT * 
FROM Invoice_warehouse
ORDER BY Warehouse_number
    OFFSET cast(:start as int) ROWS FETCH NEXT cast(:limit as int) ROWS ONLY
EOL;
            }else{
                $sql = <<<EOL
SELECT count(*) FROM Invoice_warehouse WHERE Warehouse_number = :warehouse_number
EOL;
                $query = $this->pdo->prepare($sql);
                $query->bindValue("warehouse_number", $warehouse_number);
                $query->execute();
                $count_all = $query->fetch();
                $sql = <<<EOL
SELECT * 
FROM Invoice_warehouse
WHERE Warehouse_number = :warehouse_number
ORDER BY Warehouse_number
    OFFSET cast(:start as int) ROWS FETCH NEXT cast(:limit as int) ROWS ONLY
EOL;
            }
            $context['column_name'] = ['Накладная', 'Название продукта', 'Количество товара', 'Дата получения','Номер склада'];
            $context['header'] = 'Таблица накладных';
            $context['title'] = 'Накладные';
            $context['count'] = 4;

        }elseif($table == 'document'){
            if($role == 'admin'){
                $sql = <<<EOL
SELECT count(*) FROM Document_warehouse
EOL;
                $query = $this->pdo->query($sql);
                $count_all = $query->fetch();
                $sql = <<<EOL
SELECT * 
FROM Document_warehouse
ORDER BY Warehouse_number
    OFFSET cast(:start as int) ROWS FETCH NEXT cast(:limit as int) ROWS ONLY
EOL;
            }else{
                $sql = <<<EOL
SELECT count(*) FROM Document_warehouse WHERE Warehouse_number = :warehouse_number
EOL;
                $query = $this->pdo->prepare($sql);
                $query->bindValue("warehouse_number", $warehouse_number);
                $query->execute();
                $count_all = $query->fetch();
                $sql = <<<EOL
SELECT * 
FROM Document_warehouse
WHERE Warehouse_number = :warehouse_number
ORDER BY Warehouse_number
    OFFSET cast(:start as int) ROWS FETCH NEXT cast(:limit as int) ROWS ONLY
EOL;
            }
             
            $context['column_name'] = ['Документ', 'Название продукта', 'Количество товара', 'Дата доставки', 'Номер склада'];
            $context['header'] = 'Таблица документов';
            $context['title'] = 'Документы';
            $context['count'] = 4;

        }elseif($table == 'product'){
                       $sql = <<<EOL
SELECT count(*) FROM Product
EOL;
            $query = $this->pdo->query($sql);
            $count_all = $query->fetch();
            $sql = <<<EOL
SELECT * 
FROM Product
ORDER BY Name_of_product
    OFFSET cast(:start as int) ROWS FETCH NEXT cast(:limit as int) ROWS ONLY
EOL; 
            $context['column_name'] = ['Название продукта', 'Цена товара'];
            $context['header'] = 'Таблица продуктов';
            $context['title'] = 'Продукты';
            $context['count'] = 1;

        }elseif($table == 'warehouse'){
            $sql = <<<EOL
SELECT count(*) FROM Warehouse
EOL;
            $query = $this->pdo->query($sql);
            $count_all = $query->fetch();
            $sql = <<<EOL
SELECT * 
FROM Warehouse
ORDER BY Warehouse_number
    OFFSET cast(:start as int) ROWS FETCH NEXT cast(:limit as int) ROWS ONLY
EOL; 
            $context['column_name'] = ['Название склада', 'Имя кладовщика'];
            $context['header'] = 'Таблица складов';
            $context['title'] = 'Склады';
            $context['count'] = 1;

        }   
            
        $query = $this->pdo->prepare($sql);
        if($_SESSION['role'] != 'admin'){
            $query->bindValue("warehouse_number", $warehouse_number);
        }

        $query->bindValue("start", $start);
        $query->bindValue("limit", $limit);        
        $query->execute();
        $context['objects'] = $query->fetchAll();
        

        $navi = new PaginateNavigationBuilder( "/?page={page}" );
        $navi->tpl = "{page}";
        $nav = $navi->build( $limit, $count_all[0] , $cure_page ); 
        $context['nav'] = $nav;
        $context['t'] = '4';
        if($_SESSION['role'] == 'admin'){
            $context['col'] = '<th scope="col">Редактирование</th>';
        $context['row1'] = '<td>
            <div class="row">
                <div class="col-2">
                    <form action="/delete" method="POST">
                        <input type="hidden" name="id" value="';
        $context['row2'] = '">
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-times"></i>
                        </button>
                    </form>
                </div>
                <div class="col-2">
                    <a href="/edit?id=';
        $context['row3'] = '&table=';
        $context['row4'] = '"><i class="fa-solid fa-pen"></i></a>
                </div>                                    
            </div>                                 
        </td>';
        }
        return $context;
    }

}