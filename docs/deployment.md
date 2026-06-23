# Деплой и инфраструктура

## Docker

### Быстрый старт

```bash
cp .env.example .env
docker compose up -d
```

Приложение доступно на `http://localhost:8080`.

### Сервисы

| Сервис | Порт | Назначение |
|--------|------|------------|
| app | 8080 | PHP-FPM + Nginx |
| mysql | 3307 | MySQL 8.4 |

### Полезные команды

```bash
docker compose exec app php artisan migrate     # Миграции
docker compose exec app php artisan test        # Тесты
docker compose exec app php artisan tinker      # Tinker
docker compose down -v                          # Полная остановка и удаление данных
```

## CI/CD

### GitHub Actions

Файл: `.github/workflows/test.yml`

При каждом push и PR в `main`:
1. Запуск MySQL 8.4 (сервис-контейнер)
2. Установка PHP 8.3 + Composer-зависимости
3. Миграции БД
4. Прогон тестов (`php artisan test --parallel`)
5. Проверка стиля (`pint --test`)

### Makefile

```bash
make dev      # Dev-окружение (serve + vite + queue)
make test     # Тесты
make lint     # Проверка стиля
make fix      # Автоисправление стиля
make build    # Сборка фронтенда
make start    # Docker compose up
make stop     # Docker compose down
```

## Связанные документы

- [Начало работы](getting-started.md)
- [Конфигурация](configuration.md)
