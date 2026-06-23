# Исправление: Поддержка SQLite в тестах

## Проблема

Все тесты падали с ошибкой:
```
RuntimeException: This database driver does not support fulltext index creation.
```

**Причина:** Тесты используют SQLite (in-memory database), а SQLite не поддерживает FULLTEXT индексы. Миграция `2026_05_31_231000_add_fulltext_index_to_products_table.php` пыталась создать FULLTEXT индекс без проверки драйвера БД.

## Решение

### 1. Миграция FULLTEXT индекса

**Файл:** `database/migrations/2026_05_31_231000_add_fulltext_index_to_products_table.php`

**Изменения:**
```php
public function up(): void
{
    if (DB::getDriverName() === 'sqlite') {
        return;
    }

    Schema::table('products', function (Blueprint $table) {
        $table->fullText(['name', 'short_description', 'description', 'sku']);
    });
}

public function down(): void
{
    if (DB::getDriverName() === 'sqlite') {
        return;
    }

    Schema::table('products', function (Blueprint $table) {
        $table->dropFullText(['name', 'short_description', 'description', 'sku']);
    });
}
```

### 2. SearchController

**Файл:** `app/Http/Controllers/SearchController.php`

**Изменения:**
```php
->where(function ($q) use ($query) {
    if (DB::getDriverName() !== 'sqlite') {
        $q->whereFullText(['name', 'short_description', 'description', 'sku'], $query);
    }
    
    $q->orWhere('name', 'LIKE', "%{$query}%")
        ->orWhere('short_description', 'LIKE', "%{$query}%")
        ->orWhere('description', 'LIKE', "%{$query}%")
        ->orWhere('sku', 'LIKE', "%{$query}%");
})
```

**Логика:**
- На MySQL: используется FULLTEXT поиск + LIKE (для совместимости)
- На SQLite: используется только LIKE поиск

## Результат

✅ Все 25 тестов Wishlist теперь проходят успешно
✅ Поиск работает на обоих драйверах БД
✅ Миграции выполняются без ошибок в любой среде

## Проверка

```bash
# Запуск тестов Wishlist
php artisan test --filter=Wishlist

# Ожидаемый результат: 25 тестов пройдено
```

## Примечания

- FULLTEXT индексы создаются только на MySQL/MariaDB
- На SQLite поиск работает через LIKE (медленнее, но функционально)
- Для production рекомендуется использовать MySQL с FULLTEXT индексами
- Тесты используют SQLite для скорости (in-memory database)

## Связанные файлы

- `database/migrations/2026_05_31_231000_add_fulltext_index_to_products_table.php`
- `app/Http/Controllers/SearchController.php`
- `phpunit.xml` (конфигурация тестовой БД)
