<?php

namespace App\Http\Controllers;

use App\Models\DeliveryMethod;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function __construct(protected CartService $cartService)
    {
    }

    public function index(Request $request)
    {
        $cart = $this->cartService->getOrCreateCart($request);
        $items = $this->cartService->getItems($cart);
        $total = $this->cartService->getTotal($cart);

        $deliveryMethods = DeliveryMethod::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $paymentMethods = PaymentMethod::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('checkout.index', compact('cart', 'items', 'total', 'deliveryMethods', 'paymentMethods'));
    }

    public function process(Request $request)
    {
        $cart = $this->cartService->getOrCreateCart($request);
        $items = $this->cartService->getItems($cart);

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Корзина пуста');
        }

        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:255'],
            'customer_email' => ['nullable', 'email', 'max:255'],
            'delivery_method' => ['required', 'integer', 'exists:delivery_methods,id'],
            'payment_method' => ['required', 'integer', 'exists:payment_methods,id'],
            'city' => ['required', 'string', 'max:255'],
            'street' => ['required', 'string', 'max:255'],
            'house' => ['required', 'string', 'max:50'],
            'apartment' => ['nullable', 'string', 'max:50'],
        ]);

        $deliveryMethod = DeliveryMethod::where('is_active', true)->findOrFail($validated['delivery_method']);
        $paymentMethod = PaymentMethod::where('is_active', true)->findOrFail($validated['payment_method']);

        $subtotal = $this->cartService->getTotal($cart);
        $shippingAmount = (float) $deliveryMethod->price;
        $total = $subtotal + $shippingAmount;

        $order = DB::transaction(function () use ($request, $items, $validated, $deliveryMethod, $paymentMethod, $subtotal, $shippingAmount, $total) {
            $order = Order::create([
                'user_id' => $request->user()?->id,
                'number' => $this->generateOrderNumber(),
                'status' => 'new',
                'currency' => 'BYN',
                'subtotal' => $subtotal,
                'discount_amount' => 0,
                'shipping_amount' => $shippingAmount,
                'total' => $total,
                'payment_method' => $paymentMethod->code,
                'delivery_method' => $deliveryMethod->code,
                'payment_method_code' => $paymentMethod->code,
                'payment_method_name' => $paymentMethod->name,
                'delivery_method_code' => $deliveryMethod->code,
                'delivery_method_name' => $deliveryMethod->name,
                'delivery_price' => $shippingAmount,
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'customer_email' => $validated['customer_email'] ?? null,
                'shipping_address' => [
                    'city' => $validated['city'],
                    'street' => $validated['street'],
                    'house' => $validated['house'],
                    'apartment' => $validated['apartment'] ?? null,
                ],
            ]);

            foreach ($items as $item) {
                $name = $item->purchasable_type === 'App\\Models\\Sku'
                    ? ($item->purchasable->product->name ?? 'SKU #' . $item->purchasable_id)
                    : ($item->purchasable->name ?? 'Product #' . $item->purchasable_id);

                $sku = $item->purchasable_type === 'App\\Models\\Sku'
                    ? ($item->purchasable->sku ?? null)
                    : null;

                $order->items()->create([
                    'item_type' => $item->purchasable_type,
                    'item_id' => $item->purchasable_id,
                    'name' => $name,
                    'sku' => $sku,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                    'line_total' => $item->price * $item->quantity,
                    'meta' => null,
                ]);
            }

            return $order;
        });

        $this->cartService->clear($cart);

        return redirect()->route('checkout.success', ['orderNumber' => $order->number]);
    }

    public function success(string $orderNumber)
    {
        $order = Order::where('number', $orderNumber)->with('items')->firstOrFail();

        return view('checkout.success', compact('order'));
    }

    protected function generateOrderNumber(): string
    {
        do {
            $number = 'ORD-' . now()->format('Ymd') . '-' . str_pad((string) random_int(1, 999999), 6, '0', STR_PAD_LEFT);
        } while (Order::where('number', $number)->exists());

        return $number;
    }
}
