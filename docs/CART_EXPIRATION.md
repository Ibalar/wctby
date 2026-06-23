# Очистка истёкших гостевых корзин

## Обзор

Реализована автоматическая очистка истёкших гостевых корзин для предотвращения накопления устаревших данных в БД.

## Реализация

### 1. Поле `expires_at` в модели Cart

Гостевые корзины имеют поле `expires_at`, которое устанавливается на 7 дней вперёд при создании:

```php
// app/Services/CartService.php
$cart = Cart::create([
    'session_token' => $sessionToken,
    'expires_at' => now()->addDays(7),
]);
```

### 2. Автоматическая очистка при обращении

При каждом обращении к корзине через `getOrCreateCart()` проверяется срок действия:

```php
public function getOrCreateCart(Request $request): Cart
{
    // ...
    $cart = Cart::where('session_token', $sessionToken)->first();

    // Проверяем, не истекла ли корзина
    if ($cart && $this->isExpired($cart)) {
        $this->clearExpiredCart($cart);
        $cart = null;
    }

    if (!$cart) {
        $cart = Cart::create([
            'session_token' => $sessionToken,
            'expires_at' => now()->addDays(7),
        ]);
    }

    return $cart;
}
```

### 3. Проверка истечения срока

```php
public function isExpired(Cart $cart): bool
{
    if (!$cart->expires_at) {
        return false;
    }

    return $cart->expires_at->isPast();
}
```

### 4. Отказ от слияния истёкших корзин

При логине пользователя истёкшие гостевые корзины не сливаются с корзиной пользователя:

```php
public function mergeGuestCart(User $user, string $sessionToken): void
{
    $guestCart = Cart::where('session_token', $sessionToken)->first();

    if (!$guestCart) {
        return;
    }

    // Не сливаем истёкшую корзину
    if ($this->isExpired($guestCart)) {
        $this->clearExpiredCart($guestCart);
        return;
    }

    // ... слияние корзины
}
```

### 5. Artisan команда для массовой очистки

Команда `cart:cleanup` удаляет все истёкшие гостевые корзины:

```bash
php artisan cart:cleanup
```

**Оптимизация:**
- Использует транзакции для атомарности
- Массовое удаление через `whereIn()` вместо цикла
- Удаляет сначала items, затем carts

```php
DB::transaction(function () use ($query) {
    $cartIds = $query->pluck('id');
    
    CartItem::whereIn('cart_id', $cartIds)->delete();
    Cart::whereIn('id', $cartIds)->delete();
});
```

### 6. Автоматический запуск через Scheduler

Команда запускается ежедневно в 3:00:

```php
// bootstrap/app.php
->withSchedule(function (Schedule $schedule) {
    $schedule->command('cart:cleanup')->dailyAt('03:00');
})
```

## Тестирование

### Тесты команды очистки

```bash
php artisan test --filter=CartCleanupCommandTest
```

**Покрытие:**
- ✅ Удаление истёкших гостевых корзин
- ✅ Сохранение активных гостевых корзин
- ✅ Игнорирование корзин авторизованных пользователей
- ✅ Массовое удаление нескольких корзин

### Тесты автоматической очистки

```bash
php artisan test --filter=CartExpirationTest
```

**Покрытие:**
- ✅ Автоматическая очистка при обращении к истёкшей корзине
- ✅ Сохранение активной корзины
- ✅ Отказ от слияния истёкшей корзины при логине
- ✅ Слияние активной корзины при логине
- ✅ Проверка метода `isExpired()`
- ✅ Создание новой корзины с правильным сроком действия

## Использование

### Ручная очистка

```bash
# Удалить все истёкшие гостевые корзины
php artisan cart:cleanup
```

### Автоматическая очистка

Команда автоматически запускается ежедневно в 3:00 через Laravel Scheduler.

Для настройки cron на сервере:

```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

## Статистика

После выполнения команды выводится статистика:

```
Cleaning up expired guest carts...
Found 15 expired guest carts.
Successfully deleted 15 expired guest carts and their items.
```

## Преимущества

1. **Автоматическая очистка** — истёкшие корзины удаляются при обращении
2. **Массовая очистка** — команда для очистки всех истёкших корзин
3. **Защита от слияния** — истёкшие корзины не сливаются при логине
4. **Оптимизация** — массовые операции вместо циклов
5. **Атомарность** — транзакции для целостности данных
6. **Тестирование** — полное покрытие тестами

## Связанные файлы

- `app/Services/CartService.php` — логика проверки и очистки
- `app/Console/Commands/CleanupGuestCarts.php` — команда для массовой очистки
- `app/Models/Cart.php` — модель с полем `expires_at`
- `database/migrations/2025_12_12_170935_create_carts_table.php` — миграция с полем `expires_at`
- `database/factories/CartFactory.php` — фабрика для тестов
- `database/factories/CartItemFactory.php` — фабрика для тестов
- `tests/Feature/CartCleanupCommandTest.php` — тесты команды
- `tests/Feature/CartExpirationTest.php` — тесты автоматической очистки
- `bootstrap/app.php` — настройка scheduler
