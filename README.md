[![Actions Status](https://github.com/yaleksandr89/php-project-57/actions/workflows/hexlet-check.yml/badge.svg)](https://github.com/yaleksandr89/php-project-57/actions)

---

[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=yaleksandr89_php-project-57&metric=alert_status)](https://sonarcloud.io/summary/new_code?id=yaleksandr89_php-project-57)
[![Maintainability Rating](https://sonarcloud.io/api/project_badges/measure?project=yaleksandr89_php-project-57&metric=sqale_rating)](https://sonarcloud.io/summary/new_code?id=yaleksandr89_php-project-57)
[![Reliability Rating](https://sonarcloud.io/api/project_badges/measure?project=yaleksandr89_php-project-57&metric=reliability_rating)](https://sonarcloud.io/summary/new_code?id=yaleksandr89_php-project-57)
[![Security Rating](https://sonarcloud.io/api/project_badges/measure?project=yaleksandr89_php-project-57&metric=security_rating)](https://sonarcloud.io/summary/new_code?id=yaleksandr89_php-project-57)
[![Bugs](https://sonarcloud.io/api/project_badges/measure?project=yaleksandr89_php-project-57&metric=bugs)](https://sonarcloud.io/summary/new_code?id=yaleksandr89_php-project-57)

---

# Менеджер задач

Демо: https://task-manager-sy0j.onrender.com

## Описание

...

> В процессе формирования

## Команды Makefile

| Команда | Описание |
|---|---|
| `make init` | Создает `.env` из `.env.example`, если файла еще нет |
| `make build` | Собирает docker-образы |
| `make up` | Поднимает контейнеры в фоне с пересборкой |
| `make down` | Останавливает контейнеры и удаляет orphan-контейнеры |
| `make restart` | Перезапускает окружение |
| `make logs` | Показывает логи контейнеров |
| `make ps` | Показывает список контейнеров |
| `make bash` | Открывает bash в PHP-контейнере |
| `make composer-install` | Устанавливает PHP-зависимости |
| `make composer-update` | Обновляет PHP-зависимости |
| `make composer-dump-autoload` | Пересобирает autoload-карту Composer |
| `make composer-validate` | Проверяет `composer.json` |
| `make artisan` | Запускает `php artisan` в контейнере |
| `make migrate` | Выполняет миграции |
| `make migrate-fresh` | Пересоздает БД и запускает сиды |
| `make seed` | Выполняет сиды |
| `make cache-clear` | Безопасно очищает config / route / view / event cache |
| `make cache-flush` | Очищает application cache |
| `make cache-warm` | Прогревает config / route / view cache |
| `make reset` | Выполняет `composer install`, `dump-autoload` и очистку кэшей |
| `make test` | Запускает тесты |
| `make lint` | Проверяет код через Laravel Pint |
| `make lint-fix` | Исправляет стиль кода через Laravel Pint |
| `make hooks-init` | Подключает git hooks из `.githooks` |
