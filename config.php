<?php

$dbName = 'php_db'; // Имя базы данных
$dbUser = 'root'; // Имя пользователя БД
$dbPassword = 'password'; // Пароль для доступа к БД

// Создание подключения к базе данных
try {
    $pdo = new PDO("mysql:host=localhost;dbname=$dbName", $dbUser, $dbPassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error database connection: " . $e->getMessage());
}
