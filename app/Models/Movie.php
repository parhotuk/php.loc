<?php

namespace App\Models;

use PDO;
use PDOException;

class Movie
{
    private $pdo;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
    }

    // Получаем все фильмы из БД отсортированные по алфавиту
    public function getAllMovies()
    {
        try {
            $query = "SELECT * FROM movies ORDER BY title ASC";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
            $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $movies;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        return false;
    }

    // Поиск по фильмам (может меняться поле для поиска)
    public function searchMovies($searchQuery, $fieldForSearch)
    {
        try{
            $searchQuery = '%' . $searchQuery . '%';
            $query = "SELECT * FROM movies WHERE {$fieldForSearch} LIKE :searchQuery ORDER BY title ASC";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':searchQuery', $searchQuery);
            $stmt->execute();
            $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $movies;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        return false;
    }

    // Получаем из БД данные по конкретному фильму
    public function getMovie($id)
    {
        try{
            $query = "SELECT * FROM movies WHERE id = :id LIMIT 1";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $movie = $stmt->fetch();

            return $movie;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        return false;
    }

    // Сохранение фильма в БД
    public function create($movie)
    {
        try {
            $query = "INSERT INTO movies (title, year, format, stars) VALUES (:title, :year, :format, :stars)";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':title', $movie['title']);
            $stmt->bindParam(':year', $movie['year']);
            $stmt->bindParam(':format', $movie['format']);
            $stmt->bindParam(':stars', $movie['stars']);
            if($stmt->execute()) {
                return $this->pdo->lastInsertId();
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        return false;
    }

    // Удаление фильма из БД
    public function delete($id)
    {
        try {
            $query = "DELETE FROM movies WHERE id = :id LIMIT 1";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        return false;
    }

    public function checkMovieExists($movieTitle)
    {
        try {
            $query = "SELECT id FROM movies WHERE title = :title LIMIT 1";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':title', $movieTitle);
            $stmt->execute();

            if($stmt->rowCount() > 0) {
                return true;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        return false;
    }


}