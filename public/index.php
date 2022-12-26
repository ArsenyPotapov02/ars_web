<?php

require_once '../vendor/autoload.php';
require_once '../framework/autoload.php';
require_once "../controllers/Controller404.php";
require_once "../controllers/SearchController.php";
require_once "../controllers/delete_controllers/RowDeleteController.php";
require_once "../controllers/MainController.php";
require_once "../middleware/LoginRequiredMiddleWare.php";
require_once "../controllers/LoginController.php";
require_once "../controllers/LogoutController.php";
require_once "../controllers/InvoiceCraeteController.php";
require_once "../controllers/ProductCreateController.php";
require_once "../controllers/WarehouseCreateController.php";
require_once "../controllers/UpdateController.php";

$loader = new \Twig\Loader\FilesystemLoader('../views');
$twig = new \Twig\Environment($loader, [
    "debug" => true
]);
$twig->addExtension(new \Twig\Extension\DebugExtension());
$pdo = new PDO("sqlsrv:Server=192.168.56.1,1433;Database=comp", "ars", "111", []);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

session_start();

$router = new Router($twig, $pdo);
$router->add("/login", LoginController::class);
$router->add("/", MainController::class)
    ->middleware(new LoginRequiredMiddleWare());
$router->add("/search", SearchController::class);
$router->add("/logout", LogoutController::class);
$router->add("/delete", RowDeleteController::class);
$router->add("/invoice", InvoiceCraeteController::class);
$router->add("/product", ProductCreateController::class);
$router->add("/warehouse", WarehouseCreateController::class);
$router->add("/edit", UpdateController::class);


// $router->add("/table", ContentController::class);
$router->get_or_default(Controller404::class);