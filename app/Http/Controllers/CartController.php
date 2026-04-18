<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Services\CartService;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index(Request $request)
    {
        $cart = $this->cartService->getOrCreateCart($request);
        $summary = $this->cartService->getSummary($cart);

        return view('cart.index', [
            'cart' => $cart,
            'items' => $summary['items'],
            'total' => $summary['total'],
            'count' => $summary['count'],
            'savings' => $summary['savings'],
        ]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'purchasable_type' => 'required|in:sku,product',
            'purchasable_id' => 'required|integer',
            'quantity' => 'sometimes|integer|min:1',
        ]);

        $cart = $this->cartService->getOrCreateCart($request);

        try {
            $this->cartService->addItem(
                $cart,
                $request->purchasable_type,
                $request->purchasable_id,
                $request->quantity ?? 1
            );
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $e->getMessage(),
                ], 422);
            }

            return back()->with('error', $e->getMessage());
        }

        if ($request->expectsJson()) {
            return $this->cartJsonResponse($request, 'Товар добавлен в корзину');
        }

        return back()->with('success', 'Товар добавлен в корзину');
    }

    public function update(Request $request, CartItem $item)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        $this->ensureItemBelongsToCurrentCart($request, $item);
        $this->cartService->updateItem($item, $request->quantity);

        if ($request->expectsJson()) {
            return $this->cartJsonResponse($request, 'Корзина обновлена');
        }

        return back()->with('success', 'Корзина обновлена');
    }

    public function remove(Request $request, CartItem $item)
    {
        $this->ensureItemBelongsToCurrentCart($request, $item);
        $this->cartService->removeItem($item);

        if ($request->expectsJson()) {
            return $this->cartJsonResponse($request, 'Товар удалён из корзины');
        }

        return back()->with('success', 'Товар удалён из корзины');
    }

    public function clear(Request $request)
    {
        $cart = $this->cartService->getOrCreateCart($request);
        $this->cartService->clear($cart);

        if ($request->expectsJson()) {
            return $this->cartJsonResponse($request, 'Корзина очищена');
        }

        return back()->with('success', 'Корзина очищена');
    }

    public function data(Request $request)
    {
        $cart = $this->cartService->getOrCreateCart($request);
        $summary = $this->cartService->getSummary($cart);

        return response()->json([
            'count' => $summary['count'],
            'total' => $summary['total'],
        ]);
    }

    protected function ensureItemBelongsToCurrentCart(Request $request, CartItem $item): void
    {
        $cart = $this->cartService->getOrCreateCart($request);

        if (!$this->cartService->itemBelongsToCart($item, $cart)) {
            throw new NotFoundHttpException();
        }
    }

    protected function cartJsonResponse(Request $request, string $message)
    {
        $cart = $this->cartService->getOrCreateCart($request);
        $summary = $this->cartService->getSummary($cart);

        return response()->json([
            'message' => $message,
            'count' => $summary['count'],
            'total' => $summary['total'],
            'savings' => $summary['savings'],
            'offcanvas_html' => view('partials.cart-offcanvas', [
                'cartItems' => $summary['items'],
                'cartCount' => $summary['count'],
                'cartTotal' => $summary['total'],
                'cartSavings' => $summary['savings'],
            ])->render(),
        ]);
    }
}
