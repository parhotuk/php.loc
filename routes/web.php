<?php

use App\Controllers\Controller;
use App\Controllers\MovieController;
use App\Controllers\UserController;

// Тут добавляем все необходимые маршруты

$router->addRoute('/', function () {
    $url = 'http://' . $_SERVER['HTTP_HOST'] . '/login';
    header("Location: " . $url);
    exit;
});

$router->addRoute('/login', function () {
    $userController = new UserController();
    $userController->login();
});

$router->addRoute('/register', function () {
    $userController = new UserController();
    $userController->register();
});

$router->addRoute('/logout', function () {
    $movieController = new Controller();
    $movieController->logout();
});

$router->addRoute('/movies', function () {
    $movieController = new MovieController();
    $movieController->movies();
});

$router->addRoute('/movies/create', function () {
    $movieController = new MovieController();
    $movieController->addMovie();
});

$router->addRoute('/movies/import', function () {
    $movieController = new MovieController();
    $movieController->importMovies();
});

$router->addRoute('/movies/show/(\d+)', function ($id) {
    $movieController = new MovieController();
    $movieController->showMovie($id);
});

$router->addRoute('/movies/delete/(\d+)', function ($id) {
    $movieController = new MovieController();
    $movieController->deleteMovie($id);
});