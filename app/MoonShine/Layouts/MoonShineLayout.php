<?php

declare(strict_types=1);

namespace App\MoonShine\Layouts;

use MoonShine\Laravel\Layouts\AppLayout;
use MoonShine\ColorManager\Palettes\PurplePalette;
use MoonShine\ColorManager\ColorManager;
use MoonShine\Contracts\ColorManager\ColorManagerContract;
use MoonShine\Contracts\ColorManager\PaletteContract;
use App\MoonShine\Resources\Category\CategoryResource;
use MoonShine\MenuManager\MenuGroup;
use MoonShine\MenuManager\MenuItem;
use App\MoonShine\Resources\Product\ProductResource;
use App\MoonShine\Resources\Sku\SkuResource;
use App\MoonShine\Resources\Attribute\AttributeResource;
use App\MoonShine\Resources\AttributeOption\AttributeOptionResource;
use App\MoonShine\Resources\Bundle\BundleResource;
use App\MoonShine\Resources\BundleItem\BundleItemResource;
use App\MoonShine\Resources\Order\OrderResource;
use App\MoonShine\Resources\OrderItem\OrderItemResource;
use App\MoonShine\Resources\ProductProperty\ProductPropertyResource;

final class MoonShineLayout extends AppLayout
{
    /**
     * @var null|class-string<PaletteContract>
     */
    protected ?string $palette = PurplePalette::class;

    protected function assets(): array
    {
        return [
            ...parent::assets(),
        ];
    }

    protected function menu(): array
    {
        return [
            MenuGroup::make('Каталог')->setItems([
                MenuItem::make(ProductResource::class, 'Товары'),
                MenuItem::make(CategoryResource::class, 'Категории'),
                MenuItem::make(SkuResource::class, 'SKU/Варианты'),
                MenuItem::make(BundleResource::class, 'Комплекты'),
            ])->icon('shopping-bag'),
            MenuGroup::make('Характеристики')->setItems([
                MenuItem::make(AttributeResource::class, 'Характеристики'),
                MenuItem::make(AttributeOptionResource::class, 'Значения характеристик'),
            ])->icon('cog-8-tooth'),
            MenuItem::make(OrderResource::class, 'Заказы'),
            MenuItem::make(OrderItemResource::class, 'OrderItems'),
            ...parent::menu(),
            MenuItem::make(ProductPropertyResource::class, 'ProductProperties'),
        ];
    }

    /**
     * @param ColorManager $colorManager
     */
    protected function colors(ColorManagerContract $colorManager): void
    {
        parent::colors($colorManager);

        // $colorManager->primary('#00000');
    }
}
