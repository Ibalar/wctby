# Архитектура: Structured Modules (Technical Layers)

## Обзор
Structured Modules — доменно-ориентированная модульная архитектура с разделением внутри модулей по техническим слоям. Каждый модуль инкапсулирует отдельную функциональную область (каталог, корзина, оформление заказа и т.д.) со своим набором контроллеров, сервисов, репозиториев и моделей. Паттерн выбран для проекта Wct.by как практичный компромисс между простотой Layered Architecture и строгостью Explicit Architecture.

## Обоснование выбора
- **Тип проекта:** интернет-магазин со средней сложностью домена
- **Технический стек:** PHP 8.2, Laravel 12, MySQL 8.4
- **Ключевой фактор:** кодовая база уже организована по слоям (Controllers → Services → Models), но не имеет явных границ модулей. Structured Modules добавляет модульную изоляцию без избыточной сложности.

## Структура директорий

```
app/
├── Modules/                                     # ── ФУНКЦИОНАЛЬНЫЕ МОДУЛИ ──
│   ├── Catalog/                                 # Каталог товаров и категорий
│   │   ├── Controllers/                         # HTTP-обработчики, валидация запросов
│   │   │   ├── CatalogController.php
│   │   │   └── SearchController.php
│   │   ├── Services/                            # Сервисы приложения (оркестрация use-case'ов)
│   │   │   ├── CategoryService.php
│   │   │   └── BreadcrumbService.php
│   │   ├── Repositories/                        # Доступ к данным (интерфейс + реализация)
│   │   │   └── ProductRepository.php
│   │   └── Models/                              # Доменные модели / DTO
│   │       ├── Product.php
│   │       └── Category.php
│   │
│   ├── Cart/                                    # Корзина
│   │   ├── Controllers/
│   │   │   └── CartController.php
│   │   ├── Services/
│   │   │   └── CartService.php
│   │   └── Models/
│   │       ├── Cart.php
│   │       └── CartItem.php
│   │
│   ├── Checkout/                                # Оформление заказа
│   │   ├── Controllers/
│   │   │   └── CheckoutController.php
│   │   ├── Services/
│   │   │   └── (OrderService — мигрирует из общих)
│   │   └── Models/
│   │       ├── Order.php
│   │       └── Sku.php
│   │
│   ├── Profile/                                 # Личный кабинет
│   │   ├── Controllers/
│   │   │   └── ProfileController.php
│   │   └── Models/
│   │       └── Address.php
│   │
│   ├── Wishlist/                                # Список желаний
│   │   ├── Controllers/
│   │   │   └── WishlistController.php
│   │   ├── Services/
│   │   │   └── WishlistService.php
│   │   └── Models/
│   │       └── Wishlist.php
│   │
│   ├── Auth/                                    # Аутентификация
│   │   ├── Actions/Fortify/                     # Действия Fortify
│   │   ├── Controllers/
│   │   │   └── SocialAuthController.php
│   │   └── Services/
│   │       └── SocialAccountService.php
│   │
│   └── Admin/                                   # Админ-панель MoonShine
│       ├── MoonShine/                           # Ресурсы и страницы
│       └── Controllers/
│
├── Shared/                                      # ── ОБЩЕЕ (межмодульное) ──
│   ├── Models/                                  # Общие модели (User, Media, Permission)
│   │   ├── User.php
│   │   ├── Media.php
│   │   └── SocialAccount.php
│   ├── Services/                                # Общие сервисы
│   │   └── HtmlSanitizer.php
│   ├── Middleware/                              # Общие middleware
│   │   └── EnsureEmailIsVerifiedIfAuthenticated.php
│   ├── Notifications/                           # Email-уведомления
│   │   ├── OrderConfirmationNotification.php
│   │   └── NewOrderAdminNotification.php
│   └── Observers/                               # Наблюдатели моделей
│       └── CategoryObserver.php
│
├── Console/Commands/                            # Artisan-команды
└── Providers/                                   # Сервис-провайдеры
```

## Правила зависимостей

Зависимости направлены **строго вниз**: Controllers → Services → Repositories → Models.

- ✅ Controllers вызывают Services (внедрение через конструктор)
- ✅ Services вызывают Repositories и методы Models
- ✅ Repositories возвращают Models/DTO
- ✅ Modules зависят от `Shared/`, но НЕ от внутренностей других модулей
- ✅ Межмодульная коммуникация — только через публичный API модуля или `Shared/`
- ❌ Controllers НЕ вызывают Repositories напрямую (пропуск слоя)
- ❌ Services НЕ импортируют Controllers (восходящая зависимость)
- ❌ Models НЕ импортируют Services (восходящая зависимость)
- ❌ Модули НЕ зависят от внутренностей друг друга

## Коммуникация между слоями/модулями

- **Внутри модуля:** Controllers → Services → Repositories → Models, внедрение через конструктор
- **Между модулями:** через `Shared/Models` (общие сущности), `Shared/Services` (общая бизнес-логика), или явный публичный API модуля
- **Доменные события:** при необходимости — через события Laravel (`Event::dispatch()` / `Listener`)
- **Гостевые токены:** сессионные токены (cart_token, wishlist_token) для гостей, слияние при аутентификации — через сервисы соответствующих модулей

## Ключевые принципы

1. **Границы модулей:** каждый модуль инкапсулирует одну функциональную область. Другие модули используют его публичный API и никогда не обращаются к внутренним реализациям.
2. **Тонкие контроллеры:** контроллеры только валидируют входные данные, вызывают сервисы и форматируют ответ. Никакой бизнес-логики в контроллерах.
3. **Богатые доменные модели:** основная бизнес-логика, валидация и мутации состояния живут внутри моделей. Сервисы оркестрируют use-case'ы: загружают данные → вызывают методы моделей → сохраняют.
4. **Внедрение зависимостей:** сервисы получают зависимости через конструктор (property promotion в Laravel). Репозитории могут быть оформлены как интерфейсы для облегчения тестирования.
5. **Shared — минимальный:** папка `Shared/` содержит только то, что действительно используется более чем одним модулем. Не складывать туда код «на всякий случай».

## Примеры кода

### Контроллер (Catalog/Controllers/CatalogController.php)

```php
<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Controllers;

use App\Modules\Catalog\Services\CategoryService;
use App\Modules\Catalog\Services\BreadcrumbService;
use App\Modules\Catalog\Models\Product;
use Illuminate\View\View;

final class CatalogController extends Controller
{
    public function __construct(
        protected CategoryService $categoryService,
        protected BreadcrumbService $breadcrumbService,
    ) {}

    public function product(string $slug): View
    {
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->with(['category', 'media', 'skus.attributeOptions'])
            ->firstOrFail();

        $category = $product->category;
        $breadcrumbs = $this->breadcrumbService->forCategory($category);

        return view('catalog.product', compact('product', 'breadcrumbs'));
    }
}
```

### Сервис приложения (Cart/Services/CartService.php)

```php
<?php

declare(strict_types=1);

namespace App\Modules\Cart\Services;

use App\Modules\Cart\Models\Cart;
use App\Modules\Cart\Models\CartItem;
use App\Modules\Catalog\Models\Product;
use Illuminate\Support\Facades\DB;

final class CartService
{
    public function addItem(Cart $cart, Product $product, int $quantity, ?int $skuId = null): CartItem
    {
        $existingItem = $cart->items()
            ->where('purchasable_type', Product::class)
            ->where('purchasable_id', $product->id)
            ->when($skuId, fn($q) => $q->where('sku_id', $skuId))
            ->first();

        if ($existingItem) {
            $existingItem->update(['quantity' => $existingItem->quantity + $quantity]);
            return $existingItem;
        }

        return $cart->items()->create([
            'purchasable_type' => Product::class,
            'purchasable_id'   => $product->id,
            'sku_id'           => $skuId,
            'quantity'         => $quantity,
            'price'            => $product->base_price,
        ]);
    }

    public function mergeGuestCart(Cart $guestCart, Cart $userCart): void
    {
        DB::transaction(function () use ($guestCart, $userCart) {
            foreach ($guestCart->items as $item) {
                $this->addItem($userCart, $item->purchasable, $item->quantity, $item->sku_id);
            }
            $guestCart->delete();
        });
    }
}
```

### Богатая доменная модель (Catalog/Models/Product.php)

```php
<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Models;

use Illuminate\Database\Eloquent\Model;

final class Product extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'base_price', 'is_active'];

    protected function casts(): array
    {
        return [
            'base_price' => 'decimal:2',
            'is_active'  => 'boolean',
        ];
    }

    // Связи
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function skus()
    {
        return $this->hasMany(Sku::class);
    }

    // Бизнес-правила внутри модели
    public function hasAvailableStock(): bool
    {
        return $this->skus()
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->exists();
    }

    public function getLowestPrice(): float
    {
        return $this->skus()
            ->where('is_active', true)
            ->min('price') ?? $this->base_price;
    }
}
```

## Антипаттерны

- ❌ **Анемичные модели:** модели — просто набор геттеров/сеттеров без поведения. Бизнес-логика должна жить в моделях, а не в сервисах.
- ❌ **Пропуск слоя:** контроллеры, напрямую вызывающие `Product::find()` для бизнес-операций. Контроллеры должны вызывать сервисы.
- ❌ **Восходящие зависимости:** сервисы, импортирующие контроллеры; модели, импортирующие сервисы.
- ❌ **Циклические зависимости модулей:** модуль Catalog зависит от Cart, а Cart — от Catalog. Используйте Shared/ или события.
- ❌ **Разрастание Shared/:** складирование в Shared/ кода, используемого только одним модулем. Каждый класс должен иметь единственного владельца.
