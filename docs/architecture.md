[← Начало работы](getting-started.md) · [Back to README](../README.md) · [Конфигурация →](configuration.md)

# Архитектура

## Обзор

Проект следует паттерну **Structured Modules (Technical Layers)** — модульной архитектуре с разделением по функциональным областям и техническим слоям внутри каждого модуля.

Детальное описание архитектурных решений: [.ai-factory/ARCHITECTURE.md](../.ai-factory/ARCHITECTURE.md).

## Структура модулей

```
app/
├── Modules/              # Функциональные модули
│   ├── Catalog/          # Каталог товаров
│   ├── Cart/             # Корзина
│   ├── Checkout/         # Оформление заказа
│   ├── Profile/          # Личный кабинет
│   ├── Wishlist/         # Список желаний
│   ├── Auth/             # Аутентификация
│   └── Admin/            # Админ-панель MoonShine
├── Shared/               # Общие компоненты
└── Providers/            # Сервис-провайдеры
```

## Слои внутри модуля

Каждый модуль содержит:

| Слой | Назначение | Пример |
|------|------------|--------|
| `Controllers/` | HTTP-обработчики, валидация | `CatalogController.php` |
| `Services/` | Бизнес-логика, оркестрация | `CartService.php` |
| `Repositories/` | Доступ к данным | `ProductRepository.php` |
| `Models/` | Доменные сущности | `Product.php` |

## Правила зависимостей

Зависимости направлены **строго вниз**: Controllers → Services → Repositories → Models.

- ✅ Controllers зависят от Services
- ✅ Services зависят от Repositories и Models
- ❌ Controllers не вызывают Repositories напрямую
- ❌ Services не импортируют Controllers
- ❌ Модули не зависят от внутренностей других модулей

## Ключевые паттерны

### Тонкие контроллеры

Контроллеры только валидируют входные данные, вызывают сервисы и форматируют ответ. Бизнес-логика — в сервисах и моделях.

```php
final class CatalogController extends Controller
{
    public function __construct(
        protected CategoryService $categoryService,
    ) {}

    public function product(string $slug): View
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        $breadcrumbs = $this->categoryService->breadcrumbs($product->category);
        return view('catalog.product', compact('product', 'breadcrumbs'));
    }
}
```

### Богатые доменные модели

Модели содержат бизнес-правила, аксессоры и scopes. Сервисы оркестрируют use-case'ы.

```php
final class Product extends Model
{
    protected function casts(): array
    {
        return [
            'base_price' => 'decimal:2',
            'is_active'  => 'boolean',
        ];
    }

    public function hasAvailableStock(): bool
    {
        return $this->skus()->where('stock', '>', 0)->exists();
    }
}
```

### Гостевые токены

Для неавторизованных пользователей используются сессионные токены (`cart_token`, `wishlist_token`). При входе данные гостя сливаются с аккаунтом пользователя.

## See Also

- [Конфигурация](configuration.md) — настройка окружения и сервисов
- [Архитектурный документ](../.ai-factory/ARCHITECTURE.md) — полное описание паттерна
