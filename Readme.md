## Тестовое задание для компании WebbyLab

### Архитектура проекта
1. Проект написан на PHP c испольхованием БД MySQL.
2. Дополнительно используется Composer PSR-4 для автозагрузки классов.
1. Использован паттерн MVC для разделения архитектуры проекта. 
2. Для маршрутизации используется app/Router.php
3. Необходимые роуты прописываются в routes/web.php
4. Фронтенд: шаблон и виды хранятся в views/
5. CSS стили хранятся в папке css/

### Как запустить проект
1. Нужно создать MySQL БД. Входим в Phpmyadmin под пользователем root и выполняем SQL запрос: "CREATE DATABASE php_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"
2. В файле config.php нужно указать данные для подключения к БД.
3. В терминале выполняем команду: composer install
4. Тестировать сайт можно выполнив команду в терминале: php -S localhost:8000 и перейдя по ссылке
5. После первого входа на сайт автоматически будут созданы таблицы users, movies. После этого в файле index.php можно закомментировать строку номер 7: require_once 'create_tables.php';
6. На сайте регистрируем пользователя и пользуемся проектом.
