<?php

namespace App\Services;

use App\Models\Bundle;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Sku;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CartService
{
    /**
     * Получить или создать корзину для текущего пользователя/гостя
     */
    public function getOrCreateCart(Request $request): Cart
    {
        // Если авторизован — ищем по user_id
        if ($request->user()) {
            $cart = Cart::firstOrCreate(
                ['user_id' => $request->user()->id],
                ['session_token' => Str::random(40)]
            );
            return $cart;
        }

        // Для гостя — по session_token
        $sessionToken = $request->session()->get('cart_token');

        if (!$sessionToken) {
            $sessionToken = Str::random(40);
            $request->session()->put('cart_token', $sessionToken);
        }

        $cart = Cart::where('session_token', $sessionToken)->first();

        // Проверяем, не истекла ли корзина
        if ($cart && $this->isExpired($cart)) {
            $this->clearExpiredCart($cart);
            $cart = null;
        }

        if (!$cart) {
            $cart = Cart::create([
                'session_token' => $sessionToken,
                'expires_at' => now()->addDays(7),
            ]);
        }

        return $cart;
    }

    /**
     * Проверить, истекла ли корзина
     */
    public function isExpired(Cart $cart): bool
    {
        if (!$cart->expires_at) {
            return false;
        }

        return $cart->expires_at->isPast();
    }

    /**
     * Очистить истёкшую корзину
     */
    protected function clearExpiredCart(Cart $cart): void
    {
        $cart->items()->delete();
        $cart->delete();
    }

    /**
     * Добавить товар в корзину
     * @param Cart $cart
     * @param string $purchasableType 'sku' или 'product'
     * @param int $purchasableId
     * @param int $quantity
     */
    public function addItem(Cart $cart, string $purchasableType, int $purchasableId, int $quantity = 1): CartItem
    {
        if ($purchasableType === 'sku') {
            $purchasable = Sku::where('is_active', true)->findOrFail($purchasableId);
            $price = $purchasable->price;
        } elseif ($purchasableType === 'bundle') {
            $purchasable = Bundle::where('is_active', true)->findOrFail($purchasableId);
            $price = (float) ($purchasable->total_price ?? 0);
        } else {
            $purchasable = Product::where('is_active', true)->findOrFail($purchasableId);
            if ($purchasable->skus()->where('is_active', true)->exists()) {
                throw new \Exception('Этот товар имеет варианты, выберите конкретный вариант');
            }
            $price = $purchasable->base_price;
        }

        // Ищем существующий элемент
        $existingItem = $cart->items()
            ->where('purchasable_type', get_class($purchasable))
            ->where('purchasable_id', $purchasableId)
            ->first();

        $totalQuantity = ($existingItem?->quantity ?? 0) + $quantity;

        $this->validateStock($purchasable, $totalQuantity);

        if ($existingItem) {
            $existingItem->update(['quantity' => $totalQuantity]);
            return $existingItem;
        }

        return $cart->items()->create([
            'purchasable_type' => get_class($purchasable),
            'purchasable_id' => $purchasableId,
            'quantity' => $quantity,
            'price' => $price,
        ]);
    }

    /**
     * Обновить количество товара
     */
    public function updateItem(CartItem $item, int $quantity): CartItem
    {
        if ($quantity <= 0) {
            $item->delete();
            return $item;
        }

        $purchasable = $item->purchasable;
        if ($purchasable) {
            $this->validateStock($purchasable, $quantity);
        }

        $item->update(['quantity' => $quantity]);

        return $item;
    }

    protected function validateStock(Sku|Product $purchasable, int $quantity): void
    {
        if (!$purchasable instanceof Sku) {
            return;
        }

        if ($purchasable->stock === null) {
            return;
        }

        if ($quantity > $purchasable->stock) {
            $available = $purchasable->stock;
            $name = $purchasable->product?->name ?? ('SKU #' . $purchasable->id);

            if ($available <= 0) {
                throw new \Exception("Товар «{$name}» закончился на складе");
            }

            throw new \Exception("Недостаточно товара «{$name}» на складе. Доступно: {$available}");
        }
    }

    /**
     * Удалить товар из корзины
     */
    public function removeItem(CartItem $item): void
    {
        $item->delete();
    }

    /**
     * Получить общую сумму корзины
     */
    public function getTotal(Cart $cart): float
    {
        return $this->calculateTotal($this->getItems($cart));
    }

    /**
     * Получить элементы корзины с данными о товарах
     */
    public function getItems(Cart $cart)
    {
        $items = $cart->items()->with('purchasable')->get();

        $items->loadMorph('purchasable', [
            Sku::class => ['product.media', 'attributeOptions.attribute'],
            Product::class => ['media'],
            Bundle::class => ['media', 'items.product.media'],
        ]);

        return $items;
    }

    /**
     * Получить экономию по корзине от зачёркнутых цен
     */
    public function getSavings(Cart $cart): float
    {
        return $this->calculateSavings($this->getItems($cart));
    }

    public function getOldPriceForItem(CartItem $item): ?float
    {
        $purchasable = $item->purchasable;

        if ($purchasable instanceof Sku) {
            return $purchasable->old_price ? (float) $purchasable->old_price : null;
        }

        return null;
    }

    public function resolveItemProduct(CartItem $item): ?Product
    {
        $purchasable = $item->purchasable;

        if ($purchasable instanceof Sku) {
            return $purchasable->product;
        }

        return $purchasable instanceof Product ? $purchasable : null;
    }

    public function itemBelongsToCart(CartItem $item, Cart $cart): bool
    {
        return (int) $item->cart_id === (int) $cart->id;
    }

    /**
     * Очистить корзину
     */
    public function clear(Cart $cart): void
    {
        $cart->items()->delete();
    }

    /**
     * Слить гостевую корзину с корзиной пользователя после авторизации
     */
    public function mergeGuestCart(User $user, string $sessionToken): void
    {
        $guestCart = Cart::where('session_token', $sessionToken)->first();

        if (!$guestCart) {
            return;
        }

        // Не сливаем истёкшую корзину
        if ($this->isExpired($guestCart)) {
            $this->clearExpiredCart($guestCart);
            return;
        }

        $userCart = Cart::firstOrCreate(
            ['user_id' => $user->id],
            ['session_token' => Str::random(40)]
        );

        // Переносим товары
        foreach ($guestCart->items as $item) {
            $existingItem = $userCart->items()
                ->where('purchasable_type', $item->purchasable_type)
                ->where('purchasable_id', $item->purchasable_id)
                ->first();

            if ($existingItem) {
                $existingItem->increment('quantity', $item->quantity);
            } else {
                $item->update(['cart_id' => $userCart->id]);
            }
        }

        // Удаляем гостевую корзину
        $guestCart->delete();
    }

    /**
     * Получить количество товаров в корзине
     */
    public function getItemsCount(Cart $cart): int
    {
        return $this->calculateCount($this->getItems($cart));
    }

    /**
     * Получить сводку корзины без повторных запросов.
     *
     * @return array{items: Collection<int, CartItem>, count: int, total: float, savings: float}
     */
    public function getSummary(Cart $cart): array
    {
        $items = $this->getItems($cart);

        return [
            'items' => $items,
            'count' => $this->calculateCount($items),
            'total' => $this->calculateTotal($items),
            'savings' => $this->calculateSavings($items),
        ];
    }

    /**
     * @param Collection<int, CartItem> $items
     */
    protected function calculateCount(Collection $items): int
    {
        return (int) $items->sum('quantity');
    }

    /**
     * @param Collection<int, CartItem> $items
     */
    protected function calculateTotal(Collection $items): float
    {
        return (float) $items->sum(
            fn (CartItem $item): float => (float) $item->price * (int) $item->quantity
        );
    }

    /**
     * @param Collection<int, CartItem> $items
     */
    protected function calculateSavings(Collection $items): float
    {
        return (float) $items->sum(function (CartItem $item): float {
            $oldPrice = $this->getOldPriceForItem($item);

            if (!$oldPrice || $oldPrice <= $item->price) {
                return 0;
            }

            return (float) (($oldPrice - $item->price) * $item->quantity);
        });
    }
}
