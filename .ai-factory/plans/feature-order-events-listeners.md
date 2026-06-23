# План реализации: События и слушатели

Ветка: feature/order-events-listeners
Создан: 2026-06-23

## Настройки
- Тестирование: да
- Логирование: стандартное (INFO)
- Документация: да (обязательный чекпоинт в /aif-implement)

## Привязка к дорожной карте
Веха: "События и слушатели"
Обоснование: проект полностью лишён событийной архитектуры. Контроллеры напрямую очищают корзину и шлют уведомления. OrderStatusChangedNotification создан, но нигде не вызывается.

## План коммитов
- **Коммит 1** (задачи 1-3): `feat(order): add OrderCreated and OrderStatusChanged events with EventServiceProvider`
- **Коммит 2** (задачи 4-7): `feat(order): add listeners for cart clearing and notifications`
- **Коммит 3** (задачи 8-9): `refactor(order): move side effects from controller to event listeners`
- **Коммит 4** (задача 10): `test(order): add event and listener tests`

## Задачи

### Фаза 1: События и провайдер
- [ ] **Задача 1: Создать событие OrderCreated**
  - Создать `app/Events/OrderCreated.php` — implements `ShouldDispatchAfterCommit`
  - Поля: `$order`, `$cart`
  - Логирование: не требуется (стандартное событие)

- [ ] **Задача 2: Создать событие OrderStatusChanged**
  - Создать `app/Events/OrderStatusChanged.php` — implements `ShouldDispatchAfterCommit`
  - Поля: `$order`, `$oldStatus` (OrderStatus), `$newStatus` (OrderStatus)
  - Логирование: INFO `[OrderStatusChanged] dispatched` с order_id, old, new

- [ ] **Задача 3: Создать EventServiceProvider**
  - Создать `app/Providers/EventServiceProvider.php` — extends `ServiceProvider`
  - Массив `$listen`: OrderCreated → [ClearCart, SendOrderConfirmation, SendNewOrderAdminNotification], OrderStatusChanged → [SendOrderStatusChangedNotification]
  - Метод `boot()`: `Event::listen(...)` или использовать `$listen` + parent call
  - Зарегистрировать провайдер в `bootstrap/providers.php`

### Фаза 2: Слушатели
- [ ] **Задача 4: Создать ClearCart listener**
  - Создать `app/Listeners/ClearCart.php`
  - Конструктор: `CartService` (внедрение)
  - Метод `handle(OrderCreated $event)`: `$this->cartService->clear($event->cart)`
  - Логирование: INFO `[ClearCart] cart cleared`, order_id

- [ ] **Задача 5: Создать SendOrderConfirmation listener**
  - Создать `app/Listeners/SendOrderConfirmation.php`
  - Метод `handle(OrderCreated $event)`: отправить `OrderConfirmationNotification` клиенту (если user_id) или на email (если customer_email)
  - Логирование: INFO `[SendOrderConfirmation] sent`, order_id, email
  - Перенести логику из `CheckoutController::sendOrderNotifications()` (только часть про клиента)

- [ ] **Задача 6: Создать SendNewOrderAdminNotification listener**
  - Создать `app/Listeners/SendNewOrderAdminNotification.php`
  - Метод `handle(OrderCreated $event)`: отправить `NewOrderAdminNotification` на `config('mail.admin_email')`
  - Логирование: INFO `[SendNewOrderAdminNotification] sent`, order_id, admin_email
  - Перенести логику из `CheckoutController::sendOrderNotifications()` (часть про админа)

- [ ] **Задача 7: Создать SendOrderStatusChangedNotification listener**
  - Создать `app/Listeners/SendOrderStatusChangedNotification.php`
  - Метод `handle(OrderStatusChanged $event)`: отправить `OrderStatusChangedNotification` клиенту
  - Логирование: INFO `[SendOrderStatusChangedNotification] sent`, order_id, old, new

### Фаза 3: Интеграция
- [ ] **Задача 8: Обновить CheckoutController — dispatch OrderCreated**
  - Удалить `sendOrderNotifications()` метод полностью
  - Заменить `$this->cartService->clear($cart)` и `$this->sendOrderNotifications($order)` на `event(new OrderCreated($order, $cart))`
  - Удалить неиспользуемые импорты (OrderConfirmationNotification, NewOrderAdminNotification)
  - Логирование: INFO `[CheckoutController] OrderCreated dispatched`, order_id

- [ ] **Задача 9: Обновить Order::transitionTo() — dispatch OrderStatusChanged**
  - Добавить `event(new OrderStatusChanged($this, $oldStatus, $newStatus))` в метод `transitionTo()` после `$this->status = $newStatus`
  - Логирование: уже есть в методе (INFO)

### Фаза 4: Тесты
- [ ] **Задача 10: Тесты событий и слушателей**
  - Создать `tests/Feature/OrderEventsTest.php`:
    - `test_order_created_event_dispatches_listeners()` — Event::fake(), проверить что OrderCreated dispatched + 3 слушателя
    - `test_order_status_changed_event_dispatches_listener()` — проверить что OrderStatusChanged dispatched
    - `test_cart_is_cleared_after_order()` — через listener, корзина очищена
    - `test_notifications_sent_on_order_created()` — Notification::fake(), проверить отправку
  - Логирование: стандартный PHPUnit (не требуется)
