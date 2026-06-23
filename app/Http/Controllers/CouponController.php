<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function apply(Request $request)
    {
        $code = $request->validate(['code' => 'required|string|max:50'])['code'];

        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon) {
            return back()->with('error', 'Промокод не найден');
        }

        if (!$coupon->isValid()) {
            return back()->with('error', 'Промокод недействителен или истёк');
        }

        $cart = app(\App\Services\CartService::class)->getOrCreateCart($request);
        $subtotal = app(\App\Services\CartService::class)->getTotal($cart);

        if ($coupon->min_order_amount && $subtotal < $coupon->min_order_amount) {
            return back()->with('error', 'Минимальная сумма заказа: ' . $coupon->min_order_amount . ' BYN');
        }

        $discount = $coupon->calculateDiscount($subtotal);

        session()->put('coupon', [
            'id' => $coupon->id,
            'code' => $coupon->code,
            'discount' => $discount,
            'type' => $coupon->type,
            'value' => $coupon->value,
        ]);

        return back()->with('success', 'Промокод применён! Скидка: ' . number_format($discount, 2) . ' BYN');
    }

    public function remove()
    {
        session()->forget('coupon');
        return back()->with('success', 'Промокод удалён');
    }
}
