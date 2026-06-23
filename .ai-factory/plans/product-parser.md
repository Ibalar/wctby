# План разработки: Парсинг товаров с внешних сайтов

## Обзор
Система позволяет администратору ввести URL товара с внешнего сайта, автоматически извлечь название, цену, описание, изображения и характеристики, создать товар как черновик в админке. Поддерживает одиночный и массовый ввод URL.

## Архитектура

```
┌─────────────────┐     ┌──────────────────┐     ┌─────────────────┐
│  MoonShine UI   │────▶│  ParseProductJob │────▶│  HTTP Client    │
│  (URL input)    │     │  (async queue)   │     │  (Http facade)  │
└─────────────────┘     └────────┬─────────┘     └────────┬────────┘
                                 │                         │
                                 │                  ┌──────▼──────┐
                                 │                  │  DOMCrawler │
                                 │                  │  (парсинг)  │
                                 │                  └──────┬──────┘
                          ┌──────▼──────┐                 │
                          │  ParsedItem │◀────────────────┘
                          │  draft      │
                          └─────────────┘
```

## Технический стек
- **symfony/dom-crawler** — DOM-парсинг (уже в зависимостях Laravel)
- **symfony/css-selector** — CSS-селекторы (уже в зависимостях)
- **Laravel HTTP Client** — HTTP-запросы
- **Laravel Queue (database)** — асинхронная обработка

## Компоненты

### 1. Конфигурация сайтов (`config/parsers.php`)
```php
'sites' => [
    'market' => [
        'name' => 'Маркет',
        'domains' => ['market.by'],
        'selectors' => [
            'title' => 'h1.product-title',
            'price' => '.product-price .value',
            'description' => '.product-description',
            'images' => '.product-gallery img::attr(src)',
            'specs' => '.product-specs tr',
        ],
    ],
],
```

### 2. Модель ParsedItem + миграция
- `id`, `source_url`, `site_code`, `status` (pending/processing/done/failed)
- `raw_data` (json) — сырые данные парсинга
- `product_id` (nullable FK) — созданный товар
- `error_message` (nullable text)
- timestamps

### 3. ProductParserService
- `parse(string $url): array` — определяет сайт по домену, загружает HTML, применяет селекторы
- `extractImages(array $urls): void` — скачивает изображения в media-library
- `createProduct(ParsedItem $item): Product` — создаёт товар из распарсенных данных
- `parseAndCreate(string $url): Product` — полный пайплайн

### 4. ParseProductsJob
- Принимает ParsedItem ID
- Вызывает ProductParserService::parse()
- Создаёт Product через ProductParserService::createProduct()
- Обновляет статус ParsedItem

### 5. MoonShine UI
- Страница «Парсинг товаров» (ParsedItemResource)
- Форма: текстовое поле для списка URL (по одному на строку) или одиночный URL
- Таблица результатов: URL → статус → созданный товар → ошибка
- Кнопка «Спарсить всё» для массового запуска

## Задачи реализации (~8 задач)

1. **Конфигурация парсеров** — `config/parsers.php` с 2-3 сайтами
2. **Модель ParsedItem + миграция** — таблица для отслеживания
3. **ProductParserService** — HTTP fetch + DOM парсинг
4. **ParseProductsJob** — асинхронная обработка
5. **MoonShine ParsedItemResource** — список URL + статусы
6. **Страница ввода URL** — форма ввода + кнопка запуска
7. **Скачивание изображений** — через HTTP client в media-library
8. **Тесты** — мок HTTP, проверка парсинга, проверка создания товара

## Оценка: ~450 строк кода, 1 новый пакет (spatie/crawler опционально)
