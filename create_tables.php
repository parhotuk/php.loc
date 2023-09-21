<?php

try {
    // Запрос для проверки существования таблицы
    $table = 'users';
    $query = "SHOW TABLES LIKE :table";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':table', $table);
    $stmt->execute();

    // Проверяем, есть ли таблица в базе данных
    if(!$stmt->rowCount()) {
        $query = "CREATE TABLE users (id INT AUTO_INCREMENT PRIMARY KEY, login VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL)";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

try {
    // Запрос для проверки существования таблицы
    $table = 'movies';
    $query = "SHOW TABLES LIKE :table";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':table', $table);
    $stmt->execute();

    // Проверяем, есть ли таблица в базе данных
    if(!$stmt->rowCount()) {
        $query = "CREATE TABLE movies (id INT AUTO_INCREMENT PRIMARY KEY, title VARCHAR(255) NOT NULL , year YEAR NOT NULL , format VARCHAR(255) NOT NULL , stars TEXT NOT NULL) ";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}