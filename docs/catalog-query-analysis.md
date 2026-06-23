# Анализ запросов каталога

Artisan команда для анализа производительности SQL-запросов в каталоге товаров с использованием `EXPLAIN`.

## Использование

```bash
# Анализ запросов для первой активной категории
php artisan catalog:analyze-queries

# Анализ запросов для конкретной категории
php artisan catalog:analyze-queries --category=5
```

## Что анализируется

Команда проверяет производительность следующих запросов:

1. **Product list query** — основной запрос списка товаров с eager loading
2. **Price statistics query** — запрос статистики цен (min/max) с подзапросом SKU
3. **Flags query** — запрос флагов товаров для фильтров
4. **SKU min price subquery** — подзапрос минимальной цены SKU

## Интерпретация результатов

### Предупреждения (⚠)

- **Full table scan** — полный скан таблицы, отсутствует использование индексов
- **High row count** — анализируется более 1000 строк
- **Using filesort** — сортировка без индекса
- **Using temporary** — использование временной таблицы

### Успех (✓)

- **Using index** — запрос использует индекс
- **Using index: [name]** — показывается имя используемого индекса

## Пример вывода

```
Analyzing catalog query performance...

Analyzing queries for category: Смартфоны (ID: 1)

✓ Product list query

SQL: select * from `products` where `category_id` in (?, ?, ?) and `is_active` = ?
Bindings: [1,2,3,true]

EXPLAIN:
+----+-------------+----------+------+---------------+-------------+---------+-------+------+-------------+
| id | select_type | table    | type | possible_keys | key         | key_len | ref   | rows | Extra       |
+----+-------------+----------+------+---------------+-------------+---------+-------+------+-------------+
| 1  | SIMPLE      | products | ref  | category_id   | category_id | 4       | const | 15   | Using where |
+----+-------------+----------+------+---------------+-------------+---------+-------+------+-------------+

Analysis:
  ✓ Using index: category_id
```

## Рекомендации по оптимизации

Если команда показывает предупреждения:

1. **Full table scan** — проверьте, что миграция с индексами выполнена:
   ```bash
   php artisan migrate
   ```

2. **High row count** — рассмотрите добавление дополнительных индексов или фильтрацию

3. **Using filesort** — добавьте составной индекс, включающий поля ORDER BY

4. **Using temporary** — оптимизируйте GROUP BY или DISTINCT запросы

## Автоматизация

Добавьте команду в CI/CD pipeline для мониторинга производительности:

```yaml
# .github/workflows/performance.yml
- name: Analyze catalog queries
  run: php artisan catalog:analyze-queries
```

## Связанные команды

```bash
# Очистить кеш категорий
php artisan cache:clear

# Проверить индексы таблицы
php artisan db:show --table=products
```
