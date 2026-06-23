# План реализации: Статусная модель заказов

Ветка: feature/order-status-workflow
Создан: 2026-06-23

## Настройки
- Тестирование: да
- Логирование: подробное (DEBUG/INFO/ERROR)
- Документация: да (обязательный чекпоинт в /aif-implement)

## Привязка к дорожной карте
Веха: "Статусная модель заказов"
Обоснование: статус заказа сейчас — plain string без ограничений, три значения в UI, свободный ввод в админке. Нужен enum, защищённые переходы, история изменений и уведомления.

## План коммитов
- **Коммит 1** (задачи 1-3): `feat(order): add OrderStatus enum, model casts, and transition logic`
- **Коммит 2** (задачи 4-5): `feat(order): migrate MoonShine admin to enum-based status workflow`
- **Коммит 3** (задачи 6-7): `feat(order): add status change notifications and update controllers`
- **Коммит 4** (задачи 8-10): `test(order): add tests for status enum, transitions, and features`

## Задачи

### Фаза 1: Enum и модель
- [x] **Задача 1: Создать OrderStatus enum**
  - Создать `app/Enums/OrderStatus.php` — backed enum (`string`), значения: `New`, `Processing`, `Shipped`, `Delivered`, `Completed`, `Cancelled`
  - Методы: `label(): string` (русские названия), `color(): string` (Tailwind-классы для badge), `allowedTransitions(): array` (разрешённые переходы из текущего статуса)
  - Задокументировать workflow: New → Processing → Shipped → Delivered, + Completed (финальный), + Cancelled (из New/Processing)
  - Логирование: не требуется (чистый enum)

- [x] **Задача 2: Обновить модель Order + миграция**
  - Добавить `'status' => OrderStatus::class` в `$casts` модели `Order`
  - Создать миграцию: `$table->json('status_history')->nullable()->after('status')` + `$table->index('status')`
  - Методы модели: `recordStatusChange(OrderStatus $new, ?int $userId): void` (добавляет запись в status_history), `scopeByStatus()`, `isCancellable(): bool`, `canTransitionTo(OrderStatus $target): bool`
  - Логирование: `Log::info('[Order.transition]', ['order_id'=>$this->id, 'from'=>$old, 'to'=>$new, 'user_id'=>$userId])` при каждом переходе

- [x] **Задача 3: Реализовать логику переходов статусов**
  - Метод `transitionTo(OrderStatus $new, ?int $userId): void` — проверяет `canTransitionTo()`, бросает `DomainException` при невалидном переходе
  - Вызывает `recordStatusChange()` для записи истории
  - Обновить `CheckoutController::process()` — заменить `'status' => 'new'` на `'status' => OrderStatus::New`
  - Логирование: `Log::error('[Order.transition] Invalid transition', ['order_id'=>$this->id, 'from'=>$old, 'to'=>$new])` при DomainException

### Фаза 2: Админ-панель MoonShine
- [x] **Задача 4: Обновить OrderIndexPage**
  - В `app/MoonShine/Resources/Order/Pages/OrderIndexPage.php`:
    - Заменить badge-маппинг на вызов `OrderStatus::color()`
    - Фильтры и QueryTags — генерировать динамически из `OrderStatus::cases()`
    - Добавить колонку «История статусов» (кол-во записей в status_history)
  - Логирование: не требуется

- [x] **Задача 5: Обновить OrderFormPage + OrderDetailPage**
  - В `OrderFormPage`: заменить `Text('Статус', 'status')` на `Select::make('Статус', 'status')->options(...)` — опции: только разрешённые переходы из текущего статуса
  - Добавить hook `beforeSave`: вызвать `$item->transitionTo(...)` вместо прямого присваивания
  - В `OrderDetailPage`: badge с цветом статуса + таблица истории из `status_history`
  - Логирование: INFO при сохранении смены статуса через админку

### Фаза 3: Уведомления и контроллеры
- [x] **Задача 6: Создать OrderStatusChangedNotification**
  - Создать `app/Notifications/OrderStatusChangedNotification.php` — implements `ShouldQueue`
  - Поля: `$order`, `$oldStatus`, `$newStatus`
  - Каналы: mail (клиенту) + database (админу)
  - Письмо: название заказа, старый статус → новый статус, ссылка на заказ в ЛК
  - Логирование: INFO при отправке уведомления

- [x] **Задача 7: Обновить контроллеры**
  - `ProfileController::orders()` — заменить `'completed'` на `OrderStatus::Completed->value`
  - `ProfileController::orderShow()` — добавить отображение истории статусов из `status_history`
  - Логирование: не требуется (стандартный контроллер)

### Фаза 4: Тесты
- [x] **Задача 8: Тесты OrderStatus enum**
  - Создать `tests/Unit/OrderStatusTest.php`
  - `test_all_statuses_have_label_and_color()` — у каждого статуса есть label и color
  - `test_allowed_transitions_are_valid()` — разрешён только валидный workflow
  - `test_invalid_transitions_are_blocked()` — нельзя прыгнуть из New в Delivered

- [x] **Задача 9: Тесты Order model transitions**
  - Создать `tests/Unit/OrderTransitionTest.php`
  - `test_transition_records_history()` — в status_history появляется запись
  - `test_invalid_transition_throws_exception()` — DomainException при невалидном переходе
  - `test_is_cancellable_only_from_new_or_processing()` — нельзя отменить доставленный

- [x] **Задача 10: Feature-тесты админки и контроллеров**
  - Создать `tests/Feature/OrderStatusAdminTest.php` — проверить, что Select показывает только валидные переходы
  - Дополнить существующий `tests/Feature/OrderNotificationTest.php` — проверить отправку `OrderStatusChangedNotification`
  - Логирование: стандартный PHPUnit (не требуется)
