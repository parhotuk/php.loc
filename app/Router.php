<?php

namespace App;

class Router
{
    private $routes = [];

    // Добавляем все маршруты
    public function addRoute($route, $handler)
    {
        $this->routes[$route] = $handler;
    }

    // Обработка маршрута
    public function handleRequest($uri)
    {
        foreach ($this->routes as $pattern => $callback) { // Проходим по всем маршрутам

            if (preg_match("#^$pattern$#", $uri, $matches)) {

                // Удаляем первый элемент массива $matches, так как он содержит весь URI
                array_shift($matches);

                // Вызываем обработчик маршрута и передаем ему параметры из URI
                call_user_func_array($callback, $matches);

                return;
            }
        }

        // Если не найдено совпадение, можно добавить ошибку
        echo "Page not found";
    }


}