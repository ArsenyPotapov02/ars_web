<?php

class BaseTableTwigController extends TwigBaseController{
    
    public function getContext(): array
    {
        $context = parent::getContext(); 
        $role = isset($_SESSION['role']) ? $_SESSION['role'] : 'admin';
        if( $role== 'admin'){
            $context['tables'] = [0 => ['invoice', 'Накладные'],
                                    1 => ['document', 'Документы'],
                                    2 => ['product', 'Продукты'],
                                    3 => ['warehouse', 'Склады']];
            $context['add_dropdown'] = '<li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Добавление
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
              <li><a class="dropdown-item" href="/invoice">Добавление накладной</a></li>
              <li><a class="dropdown-item" href="/warehouse">Добавление склада</a></li>
              <li><a class="dropdown-item" href="/product">Добавление продукта</a></li>
            </ul>
          </li>';
          
        //   echo "<pre>";
        //   print_r($context);
        //   echo"</pre>";
        }else{
            $context['tables'] = [0 => ['invoice', 'Накладные'],
                                    1 => ['document', 'Документы']];
        }
        return $context;
    }
}