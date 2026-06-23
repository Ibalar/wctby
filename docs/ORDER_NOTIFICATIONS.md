# Уведомления о заказах (4.2.5)

## Обзор

Реализована система email-уведомлений для заказов:
- **Уведомление покупателя** о успешном оформлении заказа
- **Уведомление администратора** о новом заказе

## Реализация

### 1. OrderConfirmationNotification

**Файл:** `app/Notifications/OrderConfirmationNotification.php`

Уведомление отправляется покупателю после оформления заказа.

**Каналы доставки:**
- Email (`mail`)
- База данных (`database`)

**Содержимое:**
- Номер заказа
- Сумма заказа
- Способ доставки и оплаты
- Адрес доставки
- Список товаров с количеством и ценой
- Ссылка на просмотр заказа

**Отправка:**
- Авторизованным пользователям — через модель `User`
- Гостям — через `Notification::route('mail', $email)`

### 2. NewOrderAdminNotification

**Файл:** `app/Notifications/NewOrderAdminNotification.php`

Уведомление отправляется администратору о новом заказе.

**Каналы доставки:**
- Email (`mail`)
- База данных (`database`)

**Содержимое:**
- Информация о клиенте (имя, телефон, email)
- Детали заказа (сумма, статус, способы доставки/оплаты)
- Адрес доставки
- Список товаров
- Ссылка на заказ в админке

**Отправка:**
- Через `Notification::route('mail', $adminEmail)`

### 3. Интеграция в CheckoutController

**Файл:** `app/Http/Controllers/CheckoutController.php`

Добавлен метод `sendOrderNotifications()`:

```php
protected function sendOrderNotifications(Order $order): void
{
    $order->load('items');

    // Уведомление покупателю
    if ($order->user_id && $order->user) {
        $order->user->notify(new OrderConfirmationNotification($order));
    } elseif ($order->customer_email) {
        Notification::route('mail', $order->customer_email)
            ->notify(new OrderConfirmationNotification($order));
    }

    // Уведомление администратору
    $adminEmail = config('mail.admin_email');
    if ($adminEmail) {
        Notification::route('mail', $adminEmail)
            ->notify(new NewOrderAdminNotification($order));
    }
}
```

Метод вызывается после успешного создания заказа и очистки корзины.

### 4. Конфигурация

**Файл:** `config/mail.php`

Добавлен параметр `admin_email`:

```php
'admin_email' => env('MAIL_ADMIN_EMAIL', 'admin@example.com'),
```

**Файл:** `.env.example`

Добавлена переменная окружения:

```env
MAIL_ADMIN_EMAIL="admin@example.com"
```

### 5. Очереди

Оба уведомления реализуют интерфейс `ShouldQueue`, что означает:
- Уведомления отправляются асинхронно через очередь
- Не блокируют ответ пользователю
- Требуют запущенного queue worker

**Запуск queue worker:**

```bash
php artisan queue:work
```

Или в режиме разработки:

```bash
php artisan queue:listen
```

## Тестирование

**Файл:** `tests/Feature/OrderNotificationTest.php`

**Тесты:**
1. ✅ Уведомление отправляется авторизованному пользователю
2. ✅ Уведомление отправляется гостю по email
3. ✅ Уведомление администратору отправляется
4. ✅ Уведомление администратору не отправляется, если email не настроен
5. ✅ Уведомление покупателю содержит детали заказа
6. ✅ Уведомление администратору содержит информацию о клиенте

**Запуск тестов:**

```bash
php artisan test --filter=OrderNotificationTest
```

## Использование

### Настройка email администратора

В файле `.env`:

```env
MAIL_ADMIN_EMAIL="admin@yourstore.com"
```

### Отключение уведомлений администратору

Установите `MAIL_ADMIN_EMAIL` в пустое значение или `null`:

```env
MAIL_ADMIN_EMAIL=
```

### Настройка SMTP

Для отправки реальных email настройте SMTP в `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@yourstore.com"
MAIL_FROM_NAME="${APP_NAME}"
MAIL_ADMIN_EMAIL="admin@yourstore.com"
```

### Режим разработки

Для тестирования без отправки реальных email:

```env
MAIL_MAILER=log
```

Все email будут записаны в `storage/logs/laravel.log`.

## Структура файлов

```
app/
├── Http/Controllers/
│   └── CheckoutController.php (изменён)
└── Notifications/
    ├── OrderConfirmationNotification.php (создан)
    └── NewOrderAdminNotification.php (создан)

config/
└── mail.php (изменён)

tests/Feature/
└── OrderNotificationTest.php (создан)

.env.example (изменён)
```

## Преимущества

1. **Асинхронность** — уведомления отправляются через очередь, не блокируя пользователя
2. **Гибкость** — уведомления сохраняются в БД для истории
3. **Информативность** — полные детали заказа в email
4. **Тестируемость** — полное покрытие тестами
5. **Конфигурируемость** — email администратора настраивается через `.env`

## Дальнейшие улучшения

- Добавить SMS-уведомления
- Добавить уведомления в Telegram
- Добавить уведомления о смене статуса заказа
- Добавить email-шаблоны с HTML-вёрсткой
- Добавить возможность отписки от уведомлений

## Связанные задачи

- ✅ 4.2.5 — Уведомления о заказах (эта задача)
- ✅ 4.1.5 — Race condition при генерации номера заказа (уже исправлено)
- ✅ 4.3.9 — Rate limiting (уже реализован)
