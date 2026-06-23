# Wishlist (Избранное)

Функционал добавления товаров в избранное для авторизованных пользователей и гостей.

## Возможности

- ✅ Добавление товаров в избранное (toggle)
- ✅ Удаление товаров из избранного
- ✅ Страница избранного с пагинацией
- ✅ Счётчик товаров в header
- ✅ Поддержка гостевых wishlists (через session_token)
- ✅ Автоматическое слияние гостевого wishlist при логине
- ✅ Автоматическая очистка старых гостевых записей
- ✅ MoonShine админ-панель для управления
- ✅ Тесты (Feature + Unit)

## Структура файлов

### Backend
- `app/Models/Wishlist.php` - модель
- `app/Http/Controllers/WishlistController.php` - контроллер
- `app/Services/WishlistService.php` - сервис для слияния wishlists
- `app/Console/Commands/CleanupGuestWishlists.php` - команда очистки
- `database/migrations/2026_05_31_232000_create_wishlists_table.php` - миграция
- `database/factories/WishlistFactory.php` - фабрика для тестов

### Frontend
- `resources/views/wishlist/index.blade.php` - страница избранного
- `resources/views/components/product-card.blade.php` - кнопка toggle
- `resources/views/partials/header.blade.php` - счётчик в header
- `resources/views/layouts/main.blade.php` - JavaScript для toggle

### Admin
- `app/MoonShine/Resources/Wishlist/WishlistResource.php` - ресурс
- `app/MoonShine/Resources/Wishlist/Pages/WishlistIndexPage.php` - список
- `app/MoonShine/Resources/Wishlist/Pages/WishlistDetailPage.php` - детали

### Tests
- `tests/Feature/WishlistControllerTest.php` - тесты контроллера
- `tests/Feature/WishlistServiceTest.php` - тесты сервиса
- `tests/Unit/WishlistModelTest.php` - тесты модели

## API Endpoints

### GET /wishlist
Показать страницу избранного пользователя.

### POST /wishlist/toggle
Добавить/удалить товар из избранного (toggle).

**Request:**
```json
{
  "product_id": 123
}
```

**Response:**
```json
{
  "message": "Товар добавлен в избранное",
  "count": 5,
  "added": true
}
```

### DELETE /wishlist/{wishlist}
Удалить товар из избранного.

**Response:**
```json
{
  "message": "Товар удалён из избранного",
  "count": 4
}
```

### GET /wishlist/count
Получить количество товаров в избранном.

**Response:**
```json
{
  "count": 5
}
```

## Использование

### Добавление кнопки в карточку товара

```blade
<button 
    type="button" 
    class="btn btn-icon btn-secondary wishlist-toggle-btn"
    data-product-id="{{ $product->id }}"
    aria-label="Добавить в избранное"
>
    <i class="ci-heart"></i>
</button>
```

JavaScript автоматически обрабатывает клики по элементам с классом `.wishlist-toggle-btn`.

### Получение wishlist пользователя

```php
// Через связь
$user->wishlists()->with('product')->get();

// Через query builder
Wishlist::where('user_id', $user->id)->with('product')->get();
```

### Проверка, есть ли товар в избранном

```php
$isInWishlist = Wishlist::where('user_id', $user->id)
    ->where('product_id', $product->id)
    ->exists();
```

## Очистка старых записей

Команда для удаления гостевых wishlists старше 30 дней:

```bash
php artisan wishlist:cleanup --days=30
```

Автоматически запускается раз в неделю через scheduler.

## Миграция

```bash
php artisan migrate
```

## Тестирование

```bash
# Все тесты wishlist
php artisan test --filter=Wishlist

# Только контроллер
php artisan test tests/Feature/WishlistControllerTest.php

# Только сервис
php artisan test tests/Feature/WishlistServiceTest.php

# Только модель
php artisan test tests/Unit/WishlistModelTest.php
```

## Особенности реализации

### Гостевые wishlists
- Хранятся с `session_token` вместо `user_id`
- Автоматически сливаются с аккаунтом при логине
- Удаляются через 30 дней неактивности

### Слияние при логине
При входе пользователя:
1. Проверяется наличие `wishlist_token` в сессии
2. Все гостевые записи переносятся в аккаунт пользователя
3. Дубликаты игнорируются (unique constraint)
4. Гостевые записи удаляются
5. `wishlist_token` удаляется из сессии

### Unique constraints
- `['user_id', 'product_id']` - один товар один раз для пользователя
- `['session_token', 'product_id']` - один товар один раз для гостя

### Каскадное удаление
- При удалении пользователя удаляются все его wishlists
- При удалении товара удаляются все wishlists с этим товаром

## Интеграция с MoonShine

Ресурс доступен в админ-панели:
- Просмотр всех wishlists
- Фильтрация по пользователю
- Query tags: Все / Авторизованные / Гостевые
- Метрики: Всего / Добавлено сегодня

## Производительность

- Индексы на `user_id`, `session_token`, `product_id`
- Составной индекс `['user_id', 'created_at']` для быстрой выборки
- Eager loading связей `user` и `product`
- Пагинация на странице избранного

## Безопасность

- Проверка принадлежности wishlist пользователю при удалении
- CSRF защита на всех POST/DELETE запросах
- Rate limiting на endpoints (60 запросов в минуту)
- Валидация `product_id` (exists:products,id)
