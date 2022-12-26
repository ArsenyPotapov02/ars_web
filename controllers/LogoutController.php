<?php

class LogoutController extends BaseTableTwigController{

    public function getContext(): array
    {
        $context = parent::getContext();
        $_SESSION['is_logged'] = false;
        $_SESSION["role"] = null;
        $_SESSION["warehouse_number"] = null;
        $_SESSION["table"] = null;
        header("Location: /");
        exit;
        return $context;
    }   

}