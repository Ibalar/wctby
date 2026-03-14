<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Sku;
use App\Models\User;
use Illuminate\Http\Request;
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

        $cart = Cart::firstOrCreate(
            ['session_token' => $sessionToken],
            ['expires_at' => now()->addDays(7)]
        );

        return $cart;
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
        // Определяем модель и цену
        if ($purchasableType === 'sku') {
            $purchasable = Sku::findOrFail($purchasableId);
            $price = $purchasable->price;
        } else {
            $purchasable = Product::findOrFail($purchasableId);
            // Если у товара есть SKU — ошибка, нужно выбирать SKU
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

        if ($existingItem) {
            $existingItem->increment('quantity', $quantity);
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
        } else {
            $item->update(['quantity' => $quantity]);
        }

        return $item;
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
        return $cart->items->sum(fn($item) => $item->price * $item->quantity);
    }

    /**
     * Получить элементы корзины с данными о товарах
     */
    public function getItems(Cart $cart)
    {
        return $cart->items()->with('purchasable.product')->get();
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
        return $cart->items()->sum('quantity');
    }
}
