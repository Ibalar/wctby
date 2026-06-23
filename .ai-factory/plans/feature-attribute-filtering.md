# План реализации: Фильтрация по атрибутам

Ветка: feature/attribute-filtering
Создан: 2026-06-23

## Настройки
- Тестирование: да
- Логирование: стандартное (INFO)
- Документация: да (обязательный чекпоинт в /aif-implement)

## Привязка к дорожной карте
Веха: "Фильтрация по атрибутам"
Обоснование: EAV-модель готова, JS sidebar и option[] уже заложены, осталось добавить whereHas в бэкенд и рендеринг в сайдбар.

## Задачи

- [x] **Задача 1: Бэкенд — добавить фильтрацию по атрибутам в CategoryController**
  - В `applyProductsFiltersAndSort()`: принять `array $optionIds`, добавить `whereHas('attributeOptions', fn($q) => $q->whereIn('attribute_option_id', $optionIds))`
  - В `show()`: загрузить filterable атрибуты (`Attribute::where('is_filterable', true)->with('options')`), посчитать количество товаров на каждую опцию через pivot
  - В `filter()`: прочитать `option[]` из request, передать в `applyProductsFiltersAndSort()`
  - Передать атрибуты с опциями во view

- [x] **Задача 2: Фронтенд — рендеринг атрибутов в сайдбаре**
  - В `resources/views/catalog/category.blade.php`: добавить секцию с фильтруемыми атрибутами
  - Каждый атрибут — аккордеон/список опций (чипсы/ссылки с количеством товаров)
  - JS уже готов — `getFilters()` читает `option[]` из URL

- [x] **Задача 3: Тесты**
  - `tests/Feature/AttributeFilterTest.php`: test_filter_by_attribute_options, test_multiple_options_filter, test_ajax_filter_accepts_options
