<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Services\CartService;
use Illuminate\Support\Facades\Log;

class ClearCart
{
    public function __construct(
        protected CartService $cartService,
    ) {}

    public function handle(OrderCreated $event): void
    {
        $this->cartService->clear($event->cart);

        Log::info('[ClearCart] Cart cleared', [
            'order_id' => $event->order->id,
            'cart_id' => $event->cart->id,
        ]);
    }
}
