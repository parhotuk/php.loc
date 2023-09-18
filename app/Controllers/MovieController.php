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
        if(isset($_POST['title']) && !empty($_POST['title'])) {
            $movie = [];
            $movie['title'] = trim($_POST['title']);
            $movie['year'] = trim($_POST['year']);
            $movie['format'] = trim($_POST['format']);
            $movie['stars'] = trim($_POST['stars']);

            $movieModel = new Movie();
            $movie_id = $movieModel->create($movie); // Создаем в БД запись с фильмом

            // Если фильм успешно сохранен - редирект на страницу этого фильма
            if($movie_id) {
                header("Location: http://" . $_SERVER['HTTP_HOST'] . "/movies/show/" . $movie_id );
                exit;
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
        $movieModel->delete($id); // удаляем фильм из БД

        header("Location: {$_SERVER['HTTP_REFERER']}");
        exit;
    }

    // Импорт списка фильмов из текстового файла (строгий формат внутри файла)
    public function importMovies()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if(isset($_FILES['fileToUpload']) && $_FILES['fileToUpload']['type'] == 'text/plain') { // Только для тхт файла
                $fileContent = file_get_contents($_FILES['fileToUpload']['tmp_name']); // Получаем данные из файла
                if(!empty($fileContent)) {
                    $movies = $this->fileContentToArray($fileContent); // Обработка данных и конвертация в массив
                    if($movies) {
                        // Сохранение фильмов в БД
                        if($this->saveMovies($movies)) {
                            header("Location: http://" . $_SERVER['HTTP_HOST'] . "/movies");
                            exit;
                        }
                    }
                }
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
            $movieModel = new Movie();

            foreach ($movies as $movie) {
                if(isset($movie['title']) && isset($movie['year']) && isset($movie['format']) && isset($movie['stars'])) {
                    $movieModel->create($movie); // сохраняем в БД
                }
            }

            return true;
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

            // Обработка данных их тхт файла
            $arrayWithMovies = preg_split('/\n\s*\n/', $fileContent); // Разделяем по пустой строке - получаем каждый фильм отдельно
            foreach ($arrayWithMovies as $arrayWithMovie) {
                $arrayWithMovie = preg_split('/\n/', $arrayWithMovie); // разделяем все данные о фильме построково
                if(count($arrayWithMovie) == 4) { // если количество строк = 4
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

}