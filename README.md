# Тестовое задание PHP+MySQL+Ajax

# Программа - "Телефонная книга".

# Задача:

Организовать телефонную книгу для пользователей. Любой желающий может зарегистрироваться и создать себе телефонную книгу.

Организовать авторизацию; загрузку файлов jpg, png; редактирование и отображение информации.

# Страницы:

Страница авторизации

Страница регистрации (Требования к логину: только латинские буквы и цифры. Проверка почты на правильность. Требование к паролю: должен содержать и цифры, и буквы.)

Страница работы с книгой (все операции без перезагрузки страницы, с помощью ajax)

# Таблицы:

Таблица пользователей, поля: логин, пароль и т.д.

Таблица с записями книги: данные записей (Имя, Фамилия, телефон, email, фото-записи и т.д….)

# Функции:

Авторизация

Добавление новой записи и загрузка к ней картинки

Редактирование существующих записей

Отображение, как общего списка, так и отдельных записей, сортировка списка

создать функцию, которая переводила бы цифровое обозначение цифр в буквенное до числа 999 999999999, например, 21125 => 'двадцать одна тысяча сто двадцать пять'. Применить ее к отображению телефонного номера в отдельных записях

Выход

# Условия:

Версия PHP 5.5.38

Не использовать фреймворки и библиотеки PHP

Использовать

JQuery

Создать простой класс Db (singleton) с использованием PDO для обращений к базе MySQL

MVC-подход (разделение как минимум на контроллер и представление)

Для форм авторизации и регистрации проверка Captcha

В качестве основы для оформления использовать Bootstrap http://getbootstrap.com/

обязательная проверка полей со стороны клиента и сервера

Файл картинки не более 2Mb, только jpg, png

# Результат задания:

Файл db-structure.sql

PHP файлы

Сколько времени было потрачено на выполнение задания?
