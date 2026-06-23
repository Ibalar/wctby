<?php

namespace App\Providers;

use App\Events\OrderCreated;
use App\Events\OrderStatusChanged;
use App\Listeners\ClearCart;
use App\Listeners\SendOrderConfirmation;
use App\Listeners\SendNewOrderAdminNotification;
use App\Listeners\SendOrderStatusChangedNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        OrderCreated::class => [
            ClearCart::class,
            SendOrderConfirmation::class,
            SendNewOrderAdminNotification::class,
        ],
        OrderStatusChanged::class => [
            SendOrderStatusChangedNotification::class,
        ],
    ];
}
