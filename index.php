<?php

require_once "vendor/autoload.php";

require_once 'config.php'; // Тут заполнить данные для подключения к БД

require_once 'create_tables.php'; // Можно закомментировать после создания таблиц в БД (после первого входа на сайт)

use App\Router;

$router = new Router(); // Роутер для маршрутизации

require_once "routes/web.php"; // Тут добавляем все нужные маршруты

$url = $_SERVER['REQUEST_URI'];
$router->handleRequest($url);