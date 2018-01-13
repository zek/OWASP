<?php

use App\Router;

require_once __DIR__ . '/vendor/autoload.php';

$klein = new \Klein\Klein();
$router = new Router($klein);
$router->routes();
$klein->dispatch();
