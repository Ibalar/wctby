# План реализации: Импорт/экспорт товаров

Ветка: feature/product-import-export
Создан: 2026-06-23

## Настройки
- Тестирование: да
- Логирование: стандартное (INFO)
- Документация: да

## Привязка к дорожной карте
Веха: "Импорт/экспорт товаров"

## Задачи

- [x] **Задача 1: Artisan-команды импорта и экспорта**
  - `products:export`: выгружает все товары (name, slug, category_id, base_price, description, is_active) в CSV в `storage/app/exports/`
  - `products:import {file}`: читает CSV, создаёт/обновляет товары по slug. Обновляет: name, category_id, base_price, description, is_active. Создаёт недостающие.

- [x] **Задача 2: Интеграция в MoonShine**
  - Кнопка «Экспорт CSV» на ProductIndexPage
  - Кнопка «Импорт CSV» → SimpleModal с загрузкой файла
  - Загрузка файла, вызов команды импорта, flash-сообщение с результатом

- [x] **Задача 3: Тесты**
  - `test_export_creates_csv_file`
  - `test_import_creates_new_products`
  - `test_import_updates_existing_products`
