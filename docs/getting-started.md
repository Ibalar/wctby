[← Back to README](../README.md) · [Архитектура →](architecture.md)

# Начало работы

## Требования

- PHP 8.2+
- MySQL 8.4
- Composer 2
- Node.js 20+
- npm 10+

## Установка

### 1. Клонирование

```bash
git clone git@github.com:Ibalar/wctby.git
cd wctby
```

### 2. PHP-зависимости

```bash
composer install
```

### 3. Настройка окружения

```bash
cp .env.example .env
php artisan key:generate
```

Отредактируйте `.env` — укажите параметры подключения к БД:

```ini
DB_CONNECTION=mysql
DB_HOST=mysql-8.4.local
DB_PORT=3306
DB_DATABASE=wctby
DB_USERNAME=wctby
DB_PASSWORD=
```

### 4. База данных

```bash
php artisan migrate --seed
```

### 5. Фронтенд

```bash
npm install
npm run build
```

### 6. Запуск

```bash
php artisan serve
```

Откройте `http://localhost:8000`.

## Запуск в режиме разработки

Одной командой (сервер + Vite + очереди + логи):

```bash
composer dev
```

Или по отдельности:

```bash
npm run dev           # Vite dev-сервер (Hot Reload)
php artisan serve     # HTTP-сервер
php artisan queue:listen --tries=1  # Обработка очередей
```

## Тестирование

```bash
php artisan test
```

Тесты используют `RefreshDatabase` — база очищается между тестами. Требуется отдельная тестовая БД (см. `.env.testing`).

## Дополнительные ресурсы

- [Архитектура проекта](architecture.md)
- [Конфигурация](configuration.md)
- [Описание проекта](../.ai-factory/DESCRIPTION.md)

## See Also

- [Архитектура](architecture.md) — структура модулей и правила зависимостей
- [Конфигурация](configuration.md) — переменные окружения и настройки
