<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Показать корзину
     */
    public function index(Request $request)
    {
        $cart = $this->cartService->getOrCreateCart($request);
        $items = $this->cartService->getItems($cart);
        $total = $this->cartService->getTotal($cart);
        $count = $this->cartService->getItemsCount($cart);

        return view('cart.index', compact('cart', 'items', 'total', 'count'));
    }

    /**
     * Добавить товар в корзину (AJAX/POST)
     */
    public function add(Request $request)
    {
        $request->validate([
            'purchasable_type' => 'required|in:sku,product',
            'purchasable_id' => 'required|integer',
            'quantity' => 'sometimes|integer|min:1',
        ]);

        $cart = $this->cartService->getOrCreateCart($request);

        try {
            $item = $this->cartService->addItem(
                $cart,
                $request->purchasable_type,
                $request->purchasable_id,
                $request->quantity ?? 1
            );
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Товар добавлен в корзину');
    }

    /**
     * Обновить количество товара (AJAX/POST)
     */
    public function update(Request $request, CartItem $item)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        $this->cartService->updateItem($item, $request->quantity);

        return back()->with('success', 'Корзина обновлена');
    }

    /**
     * Удалить товар из корзины
     */
    public function remove(CartItem $item)
    {
        $this->cartService->removeItem($item);

        return back()->with('success', 'Товар удалён из корзины');
    }

    /**
     * Очистить корзину
     */
    public function clear(Request $request)
    {
        $cart = $this->cartService->getOrCreateCart($request);
        $this->cartService->clear($cart);

        return back()->with('success', 'Корзина очищена');
    }

    /**
     * Получить данные корзины (для AJAX)
     */
    public function data(Request $request)
    {
        $cart = $this->cartService->getOrCreateCart($request);

        return response()->json([
            'count' => $this->cartService->getItemsCount($cart),
            'total' => $this->cartService->getTotal($cart),
        ]);
    }
}
