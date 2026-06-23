[← Архитектура](architecture.md) · [Back to README](../README.md)

# Конфигурация

## Переменные окружения (.env)

### Приложение

| Переменная | Описание | По умолчанию |
|------------|----------|-------------|
| `APP_NAME` | Название приложения | `Wct.by` |
| `APP_ENV` | Окружение (`local`, `production`) | `local` |
| `APP_DEBUG` | Режим отладки | `true` (local) |
| `APP_URL` | URL приложения | `https://wct.local` |
| `APP_LOCALE` | Язык интерфейса | `ru` |

### База данных

| Переменная | Описание | По умолчанию |
|------------|----------|-------------|
| `DB_CONNECTION` | Драйвер БД | `mysql` |
| `DB_HOST` | Хост БД | `mysql-8.4.local` |
| `DB_PORT` | Порт БД | `3306` |
| `DB_DATABASE` | Имя БД | `wctby` |
| `DB_USERNAME` | Пользователь БД | `wctby` |
| `DB_PASSWORD` | Пароль БД | — |

### Почта

| Переменная | Описание |
|------------|----------|
| `MAIL_MAILER` | Драйвер почты (`smtp`, `log`, `mailgun`) |
| `MAIL_HOST` | SMTP-хост |
| `MAIL_PORT` | SMTP-порт |
| `MAIL_USERNAME` | Логин SMTP |
| `MAIL_PASSWORD` | Пароль SMTP |
| `MAIL_ENCRYPTION` | Шифрование (`tls`, `ssl`) |
| `MAIL_FROM_ADDRESS` | Адрес отправителя |

### Социальная аутентификация

| Переменная | Сервис |
|------------|--------|
| `GOOGLE_CLIENT_ID` | Google OAuth |
| `GOOGLE_CLIENT_SECRET` | Google OAuth |
| `GOOGLE_REDIRECT_URI` | Google OAuth |
| `YANDEX_CLIENT_ID` | Yandex OAuth |
| `YANDEX_CLIENT_SECRET` | Yandex OAuth |
| `YANDEX_REDIRECT_URI` | Yandex OAuth |

## Основные конфигурационные файлы

| Файл | Назначение |
|------|------------|
| `config/app.php` | Основные настройки приложения |
| `config/auth.php` | Guards, провайдеры, сброс пароля |
| `config/database.php` | Подключения к БД |
| `config/mail.php` | Настройки почты |
| `config/session.php` | Сессии (драйвер `file`) |
| `config/moonshine.php` | Админ-панель MoonShine |
| `config/social_auth.php` | Социальная аутентификация |
| `config/permission.php` | Права доступа (Spatie) |
| `config/media-library.php` | Медиа-библиотека (Spatie) |
| `config/telescope.php` | Отладка (Laravel Telescope) |
| `config/lfm.php` | Файловый менеджер |

## Логирование

По умолчанию: канал `stack` → `single` → `storage/logs/laravel.log`.

Уровень логирования задаётся в `.env`:
```ini
LOG_LEVEL=debug
```

## See Also

- [Начало работы](getting-started.md) — установка и первый запуск
- [Архитектура](architecture.md) — структура проекта
