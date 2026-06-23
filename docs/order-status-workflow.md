# Статусная модель заказов

## Обзор

Заказы проходят через фиксированный workflow статусов с защищёнными переходами. Каждое изменение статуса записывается в историю, может отправлять уведомления клиенту.

## Workflow

```
New → Processing → Shipped → Delivered → Completed
  ↘                ↘
    Cancelled ←──────
```

| Статус | Разрешённые переходы |
|--------|---------------------|
| New (Новый) | Processing, Cancelled |
| Processing (В обработке) | Shipped, Cancelled |
| Shipped (Отправлен) | Delivered |
| Delivered (Доставлен) | Completed |
| Completed (Завершён) | — |
| Cancelled (Отменён) | — |

## Компоненты

| Компонент | Путь | Назначение |
|-----------|------|------------|
| OrderStatus | `app/Enums/OrderStatus.php` | Backed enum, labels, colors, allowedTransitions |
| Order | `app/Models/Order.php` | Casts, transitionTo(), recordStatusChange(), isCancellable() |
| OrderIndexPage | MoonShine | Enum-based badge colors, динамические фильтры/queryTags |
| OrderFormPage | MoonShine | Select с валидными статусами |
| OrderStatusChangedNotification | `app/Notifications/` | Email + database при смене статуса |
| Migration | `2026_06_23_000001` | status_history (JSON) + index на status |

## Использование

```php
use App\Enums\OrderStatus;

// Создание заказа
$order = Order::create([...'status' => OrderStatus::New]);

// Переход статуса
$order->transitionTo(OrderStatus::Processing, userId: 1);
$order->save();

// Проверка
$order->isCancellable(); // true только для New и Processing
$order->canTransitionTo(OrderStatus::Shipped); // проверяет workflow

// История
$order->status_history; // массив [{from, to, user_id, changed_at}, ...]
```

## Тестирование

```bash
php artisan test --filter OrderStatus
php artisan test --filter OrderTransition
php artisan test --filter OrderStatusFeature
```

## Связанные документы

- [Архитектура](architecture.md)
- [Конфигурация](configuration.md)
