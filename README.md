[![Actions Status](https://github.com/yaleksandr89/php-project-57/actions/workflows/hexlet-check.yml/badge.svg)](https://github.com/yaleksandr89/php-project-57/actions)

---

[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=yaleksandr89_php-project-57&metric=alert_status)](https://sonarcloud.io/summary/new_code?id=yaleksandr89_php-project-57)
[![Maintainability Rating](https://sonarcloud.io/api/project_badges/measure?project=yaleksandr89_php-project-57&metric=sqale_rating)](https://sonarcloud.io/summary/new_code?id=yaleksandr89_php-project-57)
[![Reliability Rating](https://sonarcloud.io/api/project_badges/measure?project=yaleksandr89_php-project-57&metric=reliability_rating)](https://sonarcloud.io/summary/new_code?id=yaleksandr89_php-project-57)
[![Security Rating](https://sonarcloud.io/api/project_badges/measure?project=yaleksandr89_php-project-57&metric=security_rating)](https://sonarcloud.io/summary/new_code?id=yaleksandr89_php-project-57)
[![Bugs](https://sonarcloud.io/api/project_badges/measure?project=yaleksandr89_php-project-57&metric=bugs)](https://sonarcloud.io/summary/new_code?id=yaleksandr89_php-project-57)

---

# Менеджер задач

Демо: [https://task-manager-sy0j.onrender.com](https://task-manager-sy0j.onrender.com)

## Оглавление

- [Описание](#описание)
- [Требования](#требования)
- [Установка и запуск через Docker](#установка-и-запуск-через-docker)
    - [Первый запуск](#первый-запуск)
    - [Повторный запуск](#повторный-запуск)
    - [Остановка контейнеров](#остановка-контейнеров)
- [Демо-данные](#демо-данные)
- [Функциональность](#функциональность)
    - [Аутентификация](#аутентификация)
    - [Задачи](#задачи)
    - [Статусы задач](#статусы-задач)
    - [Метки](#метки)
- [Архитектура](#архитектура)
- [Проверка качества](#проверка-качества)
- [Команды Makefile](#команды-makefile)
- [Демонстрация работы](#демонстрация-работы)

## Описание

Менеджер задач - веб-приложение для управления задачами, статусами и метками.

Проект реализован на `Laravel 13.5.0` в рамках обучения на Hexlet и показывает основные элементы разработки CRUD-приложения: аутентификацию, работу с базой данных, связи между моделями, фильтрацию, валидацию форм, тесты, деплой и мониторинг ошибок.

В веб-приложении реализовано:

- регистрироваться и авторизовываться;
- просматривать задачи, статусы и метки;
- создавать, редактировать и удалять задачи;
- назначать задаче статус, исполнителя и метки;
- фильтровать задачи по статусу, исполнителю и меткам;
- управлять статусами задач;
- управлять метками задач.

## Требования

- `Docker`
- `Docker Compose`
- `Make`
- `Composer`

---

## Установка и запуск через Docker

### Первый запуск

Склонируйте репозиторий:

```bash
git clone https://github.com/yaleksandr89/php-project-57.git
cd php-project-57
```

Создайте `.env` из `.env.example`:

```bash
make init
```

Поднимите контейнеры:

```bash
make up
```

Установите PHP-зависимости:

```bash
make composer-install
```

Сгенерируйте ключ приложения, если он ещё не задан:

```bash
make artisan key:generate
```

Выполните миграции и загрузите демо-данные:

```bash
make migrate-fresh
```

После запуска приложение будет доступно по адресу (название можно сменить в [default.conf](docker/nginx/default.conf):

```text
http://task-manager.local
```

### Повторный запуск

```bash
make up
```

### Остановка контейнеров

```bash
make down
```
---

## Демо-данные

В проекте реализованы сиды для быстрого наполнения базы данных.

Команда:

```bash
make migrate-fresh
```

пересоздаёт базу данных и загружает:

- пользователей;
- статусы задач;
- метки;
- задачи со связями между пользователями, статусами и метками.

После загрузки демо-данных можно авторизоваться под любым созданным пользователем.

Пример:

```text
Email: user1@user1.user1
Password: user1@user1.user1
```

Также доступны пользователи от `user1@user1.user1` до `user15@user15.user15`. Пароль каждого пользователя совпадает с его email (см. реализацию [UserSeeder.php](database/seeders/UserSeeder.php)).

---

## Функциональность

### Аутентификация

- регистрация;
- вход и выход;
- редактирование профиля;
- удаление профиля.

### Задачи

- просмотр списка задач;
- просмотр отдельной задачи;
- создание задачи авторизованным пользователем;
- редактирование задачи;
- удаление задачи только её автором;
- назначение исполнителя;
- назначение статуса;
- привязка нескольких меток;
- фильтрация по статусу, исполнителю и меткам.

### Статусы задач

- просмотр списка статусов;
- создание статуса;
- редактирование статуса;
- удаление статуса;
- запрет удаления статуса, если он используется в задачах.

### Метки

- просмотр списка меток;
- создание метки;
- редактирование метки;
- удаление метки;
- запрет удаления метки, если она используется в задачах.

---

## Архитектура

Проект построен по схеме:

```text
Controller → Service → Repository
```

- `Controllers` - принимают HTTP-запросы и возвращают ответы;
- `Form Requests` - валидируют входящие данные;
- `Services` - содержат логику приложения;
- `Repositories` - отвечают за работу с данными;
- `Models` - описывают сущности и связи Eloquent;
- `Views` - отображают интерфейс через Blade-шаблоны.

---

## Проверка качества

Запуск тестов:

```bash
make test
```

Проверка стиля кода через Laravel Pint:

```bash
make lint
```

Автоматическое исправление стиля:

```bash
make lint-fix
```

---

## Команды Makefile

| Команда                       | Описание                                                      |
|-------------------------------|---------------------------------------------------------------|
| `make init`                   | Создаёт `.env` из `.env.example`, если файла ещё нет          |
| `make build`                  | Собирает Docker-образы                                        |
| `make up`                     | Поднимает контейнеры в фоне с пересборкой                     |
| `make down`                   | Останавливает контейнеры и удаляет orphan-контейнеры          |
| `make restart`                | Перезапускает окружение                                       |
| `make logs`                   | Показывает логи контейнеров                                   |
| `make ps`                     | Показывает список контейнеров                                 |
| `make bash`                   | Открывает bash в PHP-контейнере                               |
| `make composer-install`       | Устанавливает PHP-зависимости                                 |
| `make composer-update`        | Обновляет PHP-зависимости                                     |
| `make composer-dump-autoload` | Пересобирает autoload-карту Composer                          |
| `make composer-validate`      | Проверяет `composer.json`                                     |
| `make artisan`                | Запускает `php artisan` в контейнере                          |
| `make migrate`                | Выполняет миграции                                            |
| `make migrate-fresh`          | Пересоздаёт БД и запускает сиды                               |
| `make seed`                   | Выполняет сиды                                                |
| `make cache-clear`            | Безопасно очищает config / route / view / event cache         |
| `make cache-flush`            | Очищает application cache                                     |
| `make cache-warm`             | Прогревает config / route / view cache                        |
| `make reset`                  | Выполняет `composer install`, `dump-autoload` и очистку кэшей |
| `make test`                   | Запускает тесты                                               |
| `make lint`                   | Проверяет код через Laravel Pint                              |
| `make lint-fix`               | Исправляет стиль кода через Laravel Pint                      |
| `make hooks-init`             | Подключает git hooks из `.githooks`                           |

---

## Демонстрация работы

Раздел подготовлен под короткие скринкасты. Оптимально записать 3 видео: так функциональность будет показана полностью, но без перегруза.

### 1. Обзор приложения и демо-данные

Демонстрация работы:

* Яндекс.Диск: https://disk.yandex.ru/i/2EjynfbLCvdJLg
* Google Drive: https://drive.google.com/file/d/12uqgjVjzROM016D_HXs9Z90bKjrY5ZIe/view

### 2. Работа с задачами

Демонстрация работы:

* Яндекс.Диск: https://disk.yandex.ru/i/a6tgYaHrw2XczA
* Google Drive: https://drive.google.com/file/d/10bOZlNQR1_IKEaNKxV5mrP9GVdrrcmZA/view

### 3. Управление справочниками и ограничения

Демонстрация работы:

* Яндекс.Диск: https://disk.yandex.ru/i/E4ywn8Nuf8lz3Q
* Google Drive: https://drive.google.com/file/d/1E8MiMgj9HCZH75SezKBkM4i_d5CeASf0/view?usp=sharing
