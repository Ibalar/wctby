<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use MoonShine\Contracts\Core\DependencyInjection\CoreContract;
use MoonShine\Laravel\DependencyInjection\MoonShine;
use MoonShine\Laravel\DependencyInjection\MoonShineConfigurator;
use App\MoonShine\Resources\MoonShineUser\MoonShineUserResource;
use App\MoonShine\Resources\MoonShineUserRole\MoonShineUserRoleResource;
use App\MoonShine\Resources\Category\CategoryResource;
use App\MoonShine\Resources\Product\ProductResource;
use App\MoonShine\Resources\Sku\SkuResource;
use App\MoonShine\Resources\Attribute\AttributeResource;
use App\MoonShine\Resources\AttributeOption\AttributeOptionResource;
use App\MoonShine\Resources\Bundle\BundleResource;
use App\MoonShine\Resources\BundleItem\BundleItemResource;
use App\MoonShine\Resources\Order\OrderResource;
use App\MoonShine\Resources\OrderItem\OrderItemResource;
use App\MoonShine\Resources\ProductProperty\ProductPropertyResource;
use App\MoonShine\Resources\Slide\SlideResource;
use App\MoonShine\Resources\ProductAttributeOption\ProductAttributeOptionResource;

class MoonShineServiceProvider extends ServiceProvider
{
    /**
     * @param  CoreContract<MoonShineConfigurator>  $core
     */
    public function boot(CoreContract $core): void
    {
        $core
            ->resources([
                MoonShineUserResource::class,
                MoonShineUserRoleResource::class,
                CategoryResource::class,
                ProductResource::class,
                SkuResource::class,
                AttributeResource::class,
                AttributeOptionResource::class,
                BundleResource::class,
                BundleItemResource::class,
                OrderResource::class,
                OrderItemResource::class,
                ProductPropertyResource::class,
                SlideResource::class,
                ProductAttributeOptionResource::class,
            ])
            ->pages([
                ...$core->getConfig()->getPages(),
            ])
        ;
    }

}
