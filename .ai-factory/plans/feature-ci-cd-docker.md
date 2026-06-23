# План реализации: CI/CD и инфраструктура

Ветка: feature/ci-cd-docker
Создан: 2026-06-23

## Настройки
- Тестирование: да
- Документация: да

## Привязка к дорожной карте
Веха: "CI/CD и инфраструктура"

## Задачи

- [x] **Задача 1: GitHub Actions — тесты + линтинг**
  - `.github/workflows/test.yml`: PHP 8.3, MySQL service, composer install, migrate, test, lint (pint --test)
  - Триггеры: push на main, PR на main

- [x] **Задача 2: Dockerfile + docker-compose**
  - `Dockerfile`: multi-stage (build + runtime), PHP 8.3-fpm + nginx, установка зависимостей, копирование кода
  - `docker-compose.yml`: app + mysql 8.4 + redis (опционально)
  - `.dockerignore`: node_modules, vendor, .git, storage

- [x] **Задача 3: Обновление README + документация**
  - Добавить секцию «Запуск через Docker» в README
  - Создать docs/deployment.md

- [x] **Задача 4: CI-валидация**
  - `tests/Feature/CiPipelineTest.php` — smoke-тест что CI-файлы валидны (workflow существует)
