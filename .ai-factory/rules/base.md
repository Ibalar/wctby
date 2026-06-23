# Базовые правила проекта

> Автоопределённые соглашения на основе анализа кодовой базы. Редактируйте по необходимости.

## Соглашения об именовании

- **Файлы:** PascalCase для PHP-классов (`CartController.php`, `CartService.php`), kebab-case для Blade-шаблонов (`cart-offcanvas.blade.php`)
- **Классы:** PascalCase, соответствуют имени файла (PSR-4)
- **Методы:** camelCase (`getOrCreateCart()`, `generateOrderNumber()`)
- **Переменные:** camelCase (`$cartService`, `$sessionToken`)
- **Колонки БД:** snake_case (`first_name`, `is_active`, `delivery_method_code`)
- **Роуты:** именованные, dot-notation групп (`cart.index`, `checkout.process`)
- **Тестовые методы:** snake_case, описывают поведение (`test_expired_guest_cart_is_cleared_on_access()`)

## Структура модулей

| Директория | Назначение |
|---|---|
| `app/Models/` | Eloquent-модели (21 шт.) |
| `app/Http/Controllers/` | HTTP-контроллеры — тонкие, делегируют в сервисы |
| `app/Services/` | Бизнес-логика (6 сервисов) |
| `app/Actions/Fortify/` | Действия аутентификации Fortify |
| `app/Notifications/` | Email-уведомления |
| `app/Observers/` | Наблюдатели моделей |
| `app/Console/Commands/` | Artisan-команды |
| `app/MoonShine/` | Ресурсы и страницы админ-панели |
| `app/Providers/` | Сервис-провайдеры |
| `config/` | Конфигурация (включая moonshine, social_auth, permission, telescope, lfm) |
| `routes/web.php` | Все веб-роуты |
| `routes/moonshine.php` | Роуты админ-панели |
| `resources/views/` | Blade-шаблоны по разделам |
| `tests/Feature/` | Интеграционные тесты (9 шт.) |
| `tests/Unit/` | Модульные тесты (3 шт.) |

## Обработка ошибок

- `findOrFail()` / `firstOrFail()` — для несуществующих ресурсов
- `abort(403)` / `abort(404)` — для проверок авторизации
- `try/catch (\Exception)` — для восстанавливаемых ошибок с возвратом flash-сообщений
- Пользовательский `\DomainException` — для ошибок бизнес-логики
- Проверка `$request->expectsJson()` — для JSON/HTML ответов
- Повторные попытки при `QueryException` с дублирующими ключами (до 5 раз)

## Логирование

- Явные вызовы `Log::` в коде приложения отсутствуют
- Используется стандартный exception handler Laravel
- Канал: `stack` → `single` → `storage/logs/laravel.log`

## Тестирование

- **Фреймворк:** PHPUnit через `Tests\TestCase`
- **Трейт:** `RefreshDatabase` во всех тестовых классах
- **Фабрики:** `User::factory()->create()`, `Cart::factory()->create()`
- **Ассерты:** `assertDatabaseHas()`, `assertDatabaseMissing()`
- **Фейки:** `Notification::fake()`, `Notification::assertSentTo()`
- **Аутентификация:** `$this->actingAs($user)`
- **HTTP:** `$this->post(route('checkout.process'), [...])`
- **Внедрение:** `app(CartService::class)` в `setUp()`

## Доступ к базе данных

- Исключительно Eloquent ORM (без Query Builder напрямую)
- Цепочки `Model::query()->where()->with()->get()`
- `firstOrCreate()`, `findOrFail()`, `firstOrFail()`
- Eager loading с ограничениями
- `DB::transaction()` для атомарных операций
- `paginate()` для постраничного вывода
- `whereFullText()` для полнотекстового поиска с fallback на LIKE для SQLite
