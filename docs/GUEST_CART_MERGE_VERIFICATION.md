# Проверка задачи 4.2.6: mergeGuestCart нигде не вызывается

## Статус: ✅ Проблема решена

## Проблема

Метод `CartService::mergeGuestCart()` был реализован, но нигде не вызывался, поэтому гостевые корзины не сливались с корзинами пользователей после логина.

## Решение

Метод `mergeGuestCart()` теперь вызывается в двух местах:

### 1. Обычный логин через форму

**Файл:** `app/Http/Responses/LoginResponse.php`

```php
public function toResponse($request)
{
    if ($request->user()) {
        // Слияние гостевого wishlist
        $wishlistToken = $request->session()->get('wishlist_token');
        if ($wishlistToken) {
            app(WishlistService::class)->mergeGuestWishlist($request->user(), $wishlistToken);
            $request->session()->forget('wishlist_token');
        }

        // Слияние гостевой корзины
        $cartToken = $request->session()->get('cart_token');
        if ($cartToken) {
            app(CartService::class)->mergeGuestCart($request->user(), $cartToken);
            $request->session()->forget('cart_token');
        }
    }

    return redirect()->intended(route('profile.index'));
}
```

**Когда вызывается:**
- После успешного входа через форму логина
- После успешной регистрации
- После сброса пароля и входа

### 2. Логин через социальные сети

**Файл:** `app/Http/Controllers/SocialAuthController.php`

Метод `mergeWishlist()` вызывается в трёх сценариях:

```php
protected function mergeWishlist($request): void
{
    if ($request->user()) {
        $wishlistToken = $request->session()->get('wishlist_token');
        if ($wishlistToken) {
            app(\App\Services\WishlistService::class)->mergeGuestWishlist($request->user(), $wishlistToken);
            $request->session()->forget('wishlist_token');
        }

        $cartToken = $request->session()->get('cart_token');
        if ($cartToken) {
            app(\App\Services\CartService::class)->mergeGuestCart($request->user(), $cartToken);
            $request->session()->forget('cart_token');
        }
    }
}
```

**Сценарии вызова:**

1. **Строка 62** — Вход существующего пользователя через привязанный социальный аккаунт
   ```php
   Auth::login($socialAccount->user, true);
   $this->mergeWishlist(request());
   ```

2. **Строка 95** — Вход существующего пользователя по email из социальной сети
   ```php
   Auth::login($user, true);
   $this->mergeWishlist(request());
   ```

3. **Строка 109** — Создание нового пользователя через социальную сеть
   ```php
   Auth::login($user, true);
   $this->mergeWishlist(request());
   ```

## Логика слияния

### CartService::mergeGuestCart()

**Файл:** `app/Services/CartService.php`

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

    $userCart = Cart::firstOrCreate(
        ['user_id' => $user->id],
        ['session_token' => Str::random(40)]
    );

    // Переносим товары
    foreach ($guestCart->items as $item) {
        $existingItem = $userCart->items()
            ->where('purchasable_type', $item->purchasable_type)
            ->where('purchasable_id', $item->purchasable_id)
            ->first();

        if ($existingItem) {
            // Если товар уже есть в корзине пользователя — увеличиваем количество
            $existingItem->increment('quantity', $item->quantity);
        } else {
            // Если товара нет — переносим item
            $item->update(['cart_id' => $userCart->id]);
        }
    }

    // Удаляем гостевую корзину
    $guestCart->delete();
}
```

**Особенности:**
- Проверяет, не истекла ли гостевая корзина
- Объединяет количество для одинаковых товаров
- Переносит уникальные товары
- Удаляет гостевую корзину после слияния

## Тестирование

**Файл:** `tests/Feature/GuestCartMergeTest.php`

**5 интеграционных тестов:**

1. ✅ **test_guest_cart_is_merged_after_login**
   - Проверяет слияние гостевой корзины при обычном логине
   - Убеждается, что гостевая корзина удалена
   - Проверяет, что товары перенесены в корзину пользователя

2. ✅ **test_guest_cart_is_merged_after_social_login**
   - Проверяет слияние гостевой корзины при социальном логине
   - Имитирует вызов `mergeWishlist()` из SocialAuthController

3. ✅ **test_expired_guest_cart_is_not_merged**
   - Проверяет, что истёкшие гостевые корзины не сливаются
   - Убеждается, что истёкшая корзина удаляется

4. ✅ **test_guest_cart_items_are_merged_with_existing_user_cart**
   - Проверяет слияние с существующей корзиной пользователя
   - Убеждается, что количество одинаковых товаров суммируется
   - Проверяет добавление уникальных товаров

5. ✅ **test_session_cart_token_is_cleared_after_merge**
   - Проверяет, что `cart_token` удаляется из сессии после слияния

**Запуск тестов:**

```bash
php artisan test --filter=GuestCartMergeTest
```

## Проверка

### 1. Поиск вызовов mergeGuestCart

```bash
grep -r "mergeGuestCart" --include="*.php" app/
```

**Результат:**
```
app/Services/CartService.php:241:    public function mergeGuestCart(User $user, string $sessionToken): void
app/Http/Responses/LoginResponse.php:22:                app(CartService::class)->mergeGuestCart($request->user(), $cartToken);
app/Http/Controllers/SocialAuthController.php:219:                app(\App\Services\CartService::class)->mergeGuestCart($request->user(), $cartToken);
```

✅ Метод вызывается в 2 местах (LoginResponse и SocialAuthController)

### 2. Проверка синтаксиса

```bash
php -l app/Http/Responses/LoginResponse.php
php -l app/Http/Controllers/SocialAuthController.php
php -l app/Services/CartService.php
php -l tests/Feature/GuestCartMergeTest.php
```

**Результат:** Все файлы прошли проверку синтаксиса ✅

## Результат

✅ **Метод `mergeGuestCart()` вызывается при обычном логине**
✅ **Метод `mergeGuestCart()` вызывается при социальном логине (3 сценария)**
✅ **Гостевая корзина сливается с корзиной пользователя**
✅ **Истёкшие корзины не сливаются**
✅ **Количество одинаковых товаров суммируется**
✅ **`cart_token` удаляется из сессии после слияния**
✅ **5 интеграционных тестов** — все проходят

## Связь с другими задачами

- ✅ 4.2.6 — mergeGuestCart нигде не вызывается (эта задача)
- ✅ 4.2.4 — Обработка истёкших гостевых корзин (проверка истечения в mergeGuestCart)
- ✅ 4.2.2 — Wishlist (аналогичная логика слияния для wishlist)
- ✅ 4.3.9 — Rate limiting (уже реализован)

## Итог

Задача **4.2.6 полностью решена**. Метод `mergeGuestCart()` теперь вызывается при всех сценариях входа пользователя (обычный логин и социальный логин), гостевые корзины корректно сливаются с корзинами пользователей, истёкшие корзины игнорируются, а сессия очищается после слияния.
