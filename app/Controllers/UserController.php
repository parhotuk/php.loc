<?php

namespace App\Controllers;

use App\Models\User;

class UserController extends Controller
{
    // Вход на сайт для зарегистрированного пользователя
    public function login()
    {
        // Проверяем не авторизован ли пользователь
        if($this->authenticated) {
            header('Location: http://' . $_SERVER['HTTP_HOST'] . '/movies');
            exit;
        }

        // Если пост запрос - авторизуем пользователя
        if(isset($_POST['login']) && isset($_POST['password'])) {

            $login = trim($_POST['login']);
            $password = trim($_POST['password']);

            if(!empty($login) && !empty($password)) {

                $userModel = new User();
                $user = $userModel->getUserByLogin($login); // Если существует - получаем данные пользователя

                // Проверка пароля и авторизация
                if($user && password_verify($password, $user['password'])) {
                    $this->authenticate($user['id'], $user['login']);

                    // Редирект на страницу фильмов
                    header('Location: http://' . $_SERVER['HTTP_HOST'] . '/movies');
                    exit;
                }

            }

        }

        // VIEW для текущей страницы
        ob_start();
        include ("views/users/login.php");
        $content = ob_get_clean();
        $title = "Login";
        include ("views/layouts/main.php");
    }

    // Регистрация пользователя
    public function register()
    {
        // Проверяем не авторизован ли пользователь
        if($this->authenticated) {
            header('Location: http://' . $_SERVER['HTTP_HOST'] . '/movies');
            exit;
        }

        // Если пост запрос - проверим поступившие данные и регистрируем пользователя
        if(isset($_POST['login']) && isset($_POST['password']) && isset($_POST['confirm_password'])) {

            $login = trim($_POST['login']);
            $password = trim($_POST['password']);
            $confirm_password = trim($_POST['confirm_password']);

            if(!empty($login) && !empty($password) && !empty($confirm_password)) {

                if($password == $confirm_password) {

                    if($this->isValidLogin($login)) { // проверим валидный ли логин, который ввел пользователь

                        $userModel = new User();
                        $user_id = $userModel->registerUser($login, $password); // регистрируем пользователя
                        if($user_id) {
                            $this->authenticate($user_id, $login); // авторизуем его после регистрации

                            // редирект на страницу фильмов
                            header('Location: http://' . $_SERVER['HTTP_HOST'] . '/movies');
                            exit;
                        }

                    }

                }

            }

        }

        // VIEW для текущей страницы
        ob_start();
        include ("views/users/register.php");
        $content = ob_get_clean();
        $title = "Register";
        include ("views/layouts/main.php");

    }

    // Проверка логина который ввел пользователь: можно добавлять проверки для безопасности
    private function isValidLogin($login)
    {
        if(strlen($login) <= 2 || strlen($login) > 20) {
            return false;
        } elseif(!preg_match('/^[a-zA-Z0-9]+$/', $login)) {
            return false;
        } else {
            return  true;
        }
    }


}