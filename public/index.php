<?php
require '../libs/Router.php';
require '../libs/DB.php';
require '../app/controllers/HomeController.php';
use app\controllers\HomeController;

$route = new libs\Router();
$route->register('GET', '/home/index/', [HomeController::class, 'showPost']);
//$a = $route->getRouteTable();
////var_dump($a);
//$b = $route->getRoute();
//var_dump($b);
$route->dispatch();