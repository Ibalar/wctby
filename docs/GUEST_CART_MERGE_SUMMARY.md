# Проверка задачи 4.2.6: mergeGuestCart нигде не вызывается

## Статус: ✅ Проблема решена

## Краткая сводка

Метод `CartService::mergeGuestCart()` был реализован ранее, но не вызывался при логине пользователя. Теперь проблема решена — метод вызывается в **двух местах**:

1. **Обычный логин** — `app/Http/Responses/LoginResponse.php`
2. **Социальный логин** — `app/Http/Controllers/SocialAuthController.php` (3 сценария)

## Где вызывается mergeGuestCart

### 1. LoginResponse.php (обычный логин)

**Строка 22:**
```php
app(CartService::class)->mergeGuestCart($request->user(), $cartToken);
```

**Контекст:**
- Вызывается после успешного входа через форму логина
- Вызывается после успешной регистрации
- Вызывается после сброса пароля

### 2. SocialAuthController.php (социальный логин)

**Строка 219** (в методе `mergeWishlist()`):
```php
app(\App\Services\CartService::class)->mergeGuestCart($request->user(), $cartToken);
```

**Метод `mergeWishlist()` вызывается в трёх сценариях:**

1. **Строка 62** — Вход через привязанный социальный аккаунт
2. **Строка 95** — Вход по email из социальной сети
3. **Строка 109** — Создание нового пользователя через социальную сеть

## Что делает mergeGuestCart

1. Находит гостевую корзину по `session_token`
2. Проверяет, не истекла ли корзина (связь с задачей 4.2.4)
3. Находит или создаёт корзину пользователя
4. Переносит товары из гостевой корзины:
   - Если товар уже есть — увеличивает количество
   - Если товара нет — переносит item
5. Удаляет гостевую корзину
6. Очищает `cart_token` из сессии

## Тестирование

**Файл:** `tests/Feature/GuestCartMergeTest.php`

**5 интеграционных тестов:**

1. ✅ `test_guest_cart_is_merged_after_login` — слияние при обычном логине
2. ✅ `test_guest_cart_is_merged_after_social_login` — слияние при социальном логине
3. ✅ `test_expired_guest_cart_is_not_merged` — истёкшие корзины не сливаются
4. ✅ `test_guest_cart_items_are_merged_with_existing_user_cart` — слияние с существующей корзиной
5. ✅ `test_session_cart_token_is_cleared_after_merge` — очистка сессии

**Запуск:**
```bash
php artisan test --filter=GuestCartMergeTest
```

## Проверка синтаксиса

```bash
php -l app/Http/Responses/LoginResponse.php
php -l app/Http/Controllers/SocialAuthController.php
php -l app/Services/CartService.php
php -l tests/Feature/GuestCartMergeTest.php
```

**Результат:** Все файлы прошли проверку ✅

## Созданные файлы

1. ✅ `tests/Feature/GuestCartMergeTest.php` — 5 интеграционных тестов
2. ✅ `docs/GUEST_CART_MERGE_VERIFICATION.md` — подробная документация
3. ✅ `docs/GUEST_CART_MERGE_SUMMARY.md` — эта сводка

## Результат

✅ **Метод вызывается** — в 2 местах (обычный и социальный логин)
✅ **Работает корректно** — гостевые корзины сливаются с корзинами пользователей
✅ **Защита от истёкших корзин** — истёкшие корзины не сливаются (связь с 4.2.4)
✅ **Объединение товаров** — количество одинаковых товаров суммируется
✅ **Очистка сессии** — `cart_token` удаляется после слияния
✅ **5 тестов** — все проходят

## Связь с другими задачами

- ✅ 4.2.6 — mergeGuestCart нигде не вызывается (эта задача)
- ✅ 4.2.4 — Обработка истёкших гостевых корзин (проверка в mergeGuestCart)
- ✅ 4.2.2 — Wishlist (аналогичная логика mergeGuestWishlist)
- ✅ 4.2.5 — Уведомления о заказах (уже реализовано)

## Итог

Задача **4.2.6 полностью решена и проверена**. Метод `mergeGuestCart()` корректно вызывается при всех сценариях входа пользователя, гостевые корзины сливаются с корзинами пользователей, а функциональность покрыта интеграционными тестами.
