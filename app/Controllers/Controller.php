<?php

namespace App\Controllers;

class Controller
{

    protected $authenticated = false;

    public function __construct()
    {
        session_start();
        $this->checkAuth();

    }

    // Проверияем авторизован ли пользователь
    protected function checkAuth()
    {
        if(isset($_SESSION['user_id'])) {
            $this->authenticated = true;
        } else {
            $this->authenticated = false;
        }

    }

    // Авторизуем пользователя
    protected function authenticate($user_id, $login)
    {
        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_login'] = $login;
        $this->authenticated = true;
    }

    // Выход из личного кабинета
    public function logout()
    {
        session_destroy();
        unset($_SESSION['user_id']);
        unset($_SESSION['user_login']);
        $this->authenticated = false;

        header('Location: http://' . $_SERVER['HTTP_HOST'] . '/login');
        exit;
    }

}