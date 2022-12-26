<?php

class LoginController extends BaseTableTwigController{
    public $template = "login.twig";

    public function getContext(): array
    {
        $context = parent::getContext();
        
        return $context;
    }

    public function post(array $context) {
        $login = $_POST['login'];
        $password = $_POST['password'];
        if($login == 'admin'){
            if($password == 'admin'){
                $_SESSION["is_logged"] = true;
                $_SESSION["role"] = 'admin';
                header("Location: /");
                exit;
            }
        }else{
        $sql = <<<EOL
SELECT password, Warehouse_number FROM Warehouse
    WHERE Full_name_of_storekeepers = :login
EOL;
            $query =$this->pdo->prepare($sql);
            $query->bindValue("login", $login);
            $query->execute();
            $data = $query->fetch(); 
            $valid_password = $data['password']; 
            if($valid_password == $password){
                $_SESSION["is_logged"] = true;
                $_SESSION["role"] = 'storekeeper';
                $_SESSION["warehouse_number"] = $data['Warehouse_number'];
                header("Location: /");
                exit;
            }
        }
        
    }

    

}