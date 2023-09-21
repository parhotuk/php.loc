<?php

namespace App\Models;

use PDO;
use PDOException;

class User
{

    private $pdo;
    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
    }

    // Проверка не занят ли логин
    private function isLoginFree($login)
    {
        try {
            $query = "SELECT id FROM users WHERE login = :login LIMIT 1";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':login', $login);
            $stmt->execute();

            if($stmt->rowCount() > 0) {
                return false;
            } else {
                return true;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

    }

    // Сохранение информации о новом пользователе в БД
    public function registerUser($login, $password)
    {
        if($this->isLoginFree($login)) { // Проверяем не занят ли логин

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Хеширование пароля

            try {
                $query = "INSERT INTO users (login, password) VALUES (:login, :password)";
                $stmt = $this->pdo->prepare($query);
                $stmt->bindParam(':login', $login);
                $stmt->bindParam(':password', $hashedPassword);

                if($stmt->execute()) {
                    return $this->pdo->lastInsertId();
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }

        }

        return false;
    }

    // По логину возвращаем данные пользователя
    public function getUserByLogin($login)
    {
        try {
            $query = "SELECT * FROM users WHERE login = :login LIMIT 1";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':login', $login);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if($user) {
                return $user;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        return false;
    }

}