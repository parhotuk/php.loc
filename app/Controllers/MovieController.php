<?php

namespace App\Controllers;

use App\Models\Movie;

class MovieController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        // Если пользователь НЕ авторизован, редирект на страницу авторизации
        if(!$this->authenticated) {
            header('Location: http://' . $_SERVER['HTTP_HOST'] . '/login');
            exit;
        }
    }

    // Просмотр всех фильмов + поиск по двум разным полям
    public function movies()
    {
        $movieModel = new Movie();

        // Если пришел пост запрос - значит выполним поиск по фильмам
        if(isset($_POST['text'])) {

            $searchQuery = trim($_POST['text']);
            $searchQuery = preg_replace("/[^a-zA-Z0-9-\s]/", "", $searchQuery);

            // Выбираем поле для поиска в зависимости выбора пользователя
            if(isset($_POST['search_option'])) {
                $fieldForSearch = 'stars';
            } else {
                $fieldForSearch = 'title';
            }
            // выполняем поиск и получаем из БД нужные фильмы
            $movies = $movieModel->searchMovies($searchQuery, $fieldForSearch);
        } else {
            if(isset($_SESSION['errorMessage'])) {
                $errorMessage = $_SESSION['errorMessage'];
                unset($_SESSION['errorMessage']);
            }
            // Найдем все фильмы
            $movies = $movieModel->getAllMovies();
        }

        // VIEW для текущей страницы
        ob_start();
        include ("views/movies/movies.php");
        $content = ob_get_clean();
        $title = "Movies";
        include ("views/layouts/main.php");
    }

    // Добавление фильма
    public function addMovie()
    {
        if(!empty($_POST)) {

            $movie = $this->validateMovieData($_POST);
            if($movie) {

                $movieModel = new Movie();
                // Проверяем не добавлен ли еще фильм с таким названием
                if(!$movieModel->checkMovieExists($movie['title'])) {

                    $movie_id = $movieModel->create($movie); // Создаем в БД запись с фильмом

                    // Если фильм успешно сохранен - редирект на страницу этого фильма
                    if($movie_id) {
                        header("Location: http://" . $_SERVER['HTTP_HOST'] . "/movies/show/" . $movie_id );
                        exit;
                    }

                } else {
                    // Фильм уже добавлен
                    $errorMessage = "The movie with this title already exists!";
                }

            } else {
                // Нужно заполнить все поля
                $errorMessage = "All fields are required!";
            }

        }

        // VIEW для текущей страницы
        ob_start();
        include ("views/movies/create.php");
        $content = ob_get_clean();
        $title = "Add movie";
        include ("views/layouts/main.php");
    }

    // Просмотр полной информации о фильме
    public function showMovie($id)
    {
        $movieModel = new Movie();
        $movie = $movieModel->getMovie($id); // находим нужный фильм

        // VIEW для текущей страницы
        ob_start();
        include ("views/movies/show.php");
        $content = ob_get_clean();
        $title = "Movie: " . $movie['title'];
        include ("views/layouts/main.php");
    }

    // Удаление фильма
    public function deleteMovie($id)
    {
        $movieModel = new Movie();
        if($movieModel->delete($id)) {
            $_SESSION['errorMessage'] = 'Movie was deleted!';
        }

        header("Location: {$_SERVER['HTTP_REFERER']}");
        exit;
    }

    // Импорт списка фильмов из текстового файла (строгий формат внутри файла)
    public function importMovies()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if(isset($_FILES['fileToUpload']['name']) && !empty($_FILES['fileToUpload']['name'])) { // Только для тхт файла
                if($_FILES['fileToUpload']['type'] == 'text/plain') {
                    $fileContent = file_get_contents($_FILES['fileToUpload']['tmp_name']); // Получаем данные из файла
                    if(!empty($fileContent)) {
                        $movies = $this->fileContentToArray($fileContent); // Обработка данных и конвертация в массив
                        if($movies) {
                            // Сохранение фильмов в БД
                            $resultOfAddingMovies = $this->saveMovies($movies);
                            if($resultOfAddingMovies > 0) {
                                $successMessage = "Total imported: " . $resultOfAddingMovies;
                            } else {
                                $successMessage = "Nothing to import!";
                            }
                        }
                    }
                } else {
                    // Неверный формат файла
                    $errorMessage = "Invalid file format!";
                }
            } else {
                // Файл не выбран
                $errorMessage = "You must select txt file!";
            }
        }

        // VIEW для текущей страницы
        ob_start();
        include ("views/movies/import.php");
        $content = ob_get_clean();
        $title = "Import movies";
        include ("views/layouts/main.php");
    }

    // Сохранение массива с фильмами в БД
    public function saveMovies($movies)
    {
        if($movies && is_array($movies)) {
            $i = 0;
            $movieModel = new Movie();

            foreach ($movies as $movie) {
                $movie = $this->validateMovieData($movie); // Проверяем и валидируем данные перед сохранением
                if($movie) {
                    if(!$movieModel->checkMovieExists($movie['title'])) { // Проверяем что бы фильм еще не был добавлен по названию
                        if($movieModel->create($movie)) {
                            $i++;
                        }
                    }
                }
            }

            return $i;
        }

        return false;
    }


    // Конвертируем все данные из файла в массив
    private function fileContentToArray($fileContent)
    {
        if(!empty($fileContent)) {

            $movies = [];
            $i = 0;
            // Шаблон для имен полей в БД
            $patternForKeys = [
                'Title' => 'title',
                'Release Year' => 'year',
                'Format' => 'format',
                'Stars' => 'stars'
            ];

            // Обработка данных из тхт файла
            $arrayWithMovies = preg_split('/\n\s*\n/', $fileContent); // Разделяем по пустой строке - получаем каждый фильм отдельно
            foreach ($arrayWithMovies as $arrayWithMovie) {
                $arrayWithMovie = preg_split('/\n/', $arrayWithMovie); // разделяем данные об одном фильме построково
                if(count($arrayWithMovie) == 4) { // если количество строк = 4 продолжаем
                    foreach ($arrayWithMovie as $arrayWithMovieByLines) {
                        $partsOfLine = preg_split('/:/', $arrayWithMovieByLines, 2); // До первого символа ":" - заголовок, а после - данные
                        if(isset($patternForKeys[trim($partsOfLine[0])])) { // Из шаблона подставляем нужное имя для поля (если существует)
                            $fieldKey = $patternForKeys[trim($partsOfLine[0])];
                            if(!empty($fieldKey)) {
                                $movies[$i][$fieldKey] = trim($partsOfLine[1]); // переносим данные в массив
                            }
                        }
                    }
                }
                $i++;
            }

            return $movies;
        }

        return false;
    }

    public function validateMovieData($movie)
    {
        if(isset($movie['title']) && isset($movie['year']) && isset($movie['format']) && isset($movie['stars'])) {

            // Чистим теги и удаляем пробелы по краям
            $movie['title'] = trim(strip_tags($movie['title']));
            $movie['year'] = trim(strip_tags($movie['year']));
            $movie['format'] = trim(strip_tags($movie['format']));
            $movie['stars'] = trim(strip_tags($movie['stars']));

            if(!empty($movie['title']) && !empty($movie['year']) && !empty($movie['format']) && !empty($movie['stars'])) {
                return $movie;
            }
        }

        return false;
    }


}