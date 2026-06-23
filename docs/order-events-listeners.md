# События и слушатели

## Обзор

Проект переведён на событийную архитектуру для оформления заказов и смены статусов. Контроллеры больше не выполняют побочные эффекты напрямую — они диспатчат события, а слушатели обрабатывают их асинхронно.

## События

| Событие | Когда | Поля |
|---------|-------|------|
| `OrderCreated` | Заказ создан в CheckoutController | `$order`, `$cart` |
| `OrderStatusChanged` | Статус изменён через `Order::transitionTo()` | `$order`, `$oldStatus`, `$newStatus` |

## Слушатели

| Слушатель | Событие | Действие |
|-----------|---------|----------|
| `ClearCart` | OrderCreated | Очищает корзину через CartService |
| `SendOrderConfirmation` | OrderCreated | Отправляет OrderConfirmationNotification клиенту |
| `SendNewOrderAdminNotification` | OrderCreated | Отправляет NewOrderAdminNotification админу |
| `SendOrderStatusChangedNotification` | OrderStatusChanged | Отправляет OrderStatusChangedNotification клиенту |

## Архитектура

```
CheckoutController::process()
  → Order::create()
  → event(new OrderCreated($order, $cart))
      → ClearCart::handle()
      → SendOrderConfirmation::handle()
      → SendNewOrderAdminNotification::handle()

Order::transitionTo()
  → status change + history
  → event(new OrderStatusChanged($order, $old, $new))
      → SendOrderStatusChangedNotification::handle()
```

## Регистрация

`EventServiceProvider` зарегистрирован в `bootstrap/providers.php`. События используют `ShouldDispatchAfterCommit` — диспатчатся только после успешной транзакции.

## Тестирование

```bash
php artisan test --filter OrderEvents
```

5 тестов: проверка диспатча событий, маппинга слушателей, отправки уведомлений.

## Связанные документы

- [Статусная модель заказов](order-status-workflow.md)
- [Архитектура](architecture.md)
