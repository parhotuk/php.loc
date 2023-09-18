## Тестовое задание для компании WebbyLab

### Архитектура
1. Использована модель MVC 
2. Для маршрутизации используется app/Router.php
3. Необходимые роуты прописываются в routes/web.php
4. Фронтенд: шаблон и виды в views/
5. Стили хранятся в папке css/
6. Используется Composer PSR-4 для автозагрузки классов 

### Как запустить проект
1. Изначально нужно в database/database.php указать данные для подключения к БД
2. Выполняем команду composer install
3. Выполняем команду composer dump-autoload
4. После первого входа на сайт будут созданы таблицы users, movies. После этого в файле index.php можно закомментировать строку номер 7: require_once 'create_tables.php';
5. Регистрируем пользователя и пользуемся проектом