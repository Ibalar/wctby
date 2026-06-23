# Исправление ошибки тестов Wishlist

## Проблема
Все 25 тестов Wishlist падали с ошибкой:
```
RuntimeException: This database driver does not support fulltext index creation.
```

## Причина
Тесты используют SQLite (in-memory database), но миграция поиска создавала FULLTEXT индексы, которые не поддерживаются SQLite.

## Исправления

### 1. Миграция FULLTEXT индекса
**Файл:** `database/migrations/2026_05_31_231000_add_fulltext_index_to_products_table.php`

Добавлена проверка драйвера БД:
```php
if (DB::getDriverName() === 'sqlite') {
    return;
}
```

### 2. SearchController
**Файл:** `app/Http/Controllers/SearchController.php`

Добавлена условная логика:
- MySQL: FULLTEXT + LIKE поиск
- SQLite: только LIKE поиск

## Результат
✅ Все 25 тестов Wishlist теперь проходят
✅ Поиск работает на обоих драйверах БД
✅ Миграции выполняются без ошибок

## Проверка
```bash
php artisan test --filter=Wishlist
```

## Документация
- `docs/SQLITE_FIX.md` - подробное описание исправления
- `docs/wishlist.md` - полное руководство по Wishlist
- `docs/WISHLIST_SUMMARY.md` - итоговая сводка реализации
