<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\MoonShine\Resources\Category\CategoryResource;
use App\MoonShine\Resources\Product\ProductResource;
use MoonShine\Laravel\Pages\Page;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Components\ActionButton;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Layout\Column;
use MoonShine\UI\Components\Layout\Flex;
use MoonShine\UI\Components\Layout\Grid;
use MoonShine\UI\Components\Metrics\Wrapped\ValueMetric;
#[\MoonShine\MenuManager\Attributes\SkipMenu]

class Dashboard extends Page
{
    /**
     * @return array<string, string>
     */
    public function getBreadcrumbs(): array
    {
        return [
            '#' => $this->getTitle()
        ];
    }

    public function getTitle(): string
    {
        return $this->title ?: 'Dashboard';
    }

    /**
     * @return list<ComponentContract>
     */
    protected function components(): iterable
	{
        $todayOrders = Order::query()
            ->whereDate('created_at', today())
            ->selectRaw('COUNT(*) as total_orders, COALESCE(SUM(total), 0) as total_revenue')
            ->first();

        return [


            Box::make('Основные метрики', [
                Grid::make([
                    Column::make(
                        [
                            ValueMetric::make('Товаров')
                                ->value((int) Product::count()),
                        ],
                        colSpan: 3,
                        adaptiveColSpan: 6
                    ),
                    Column::make(
                        [
                            ValueMetric::make('Активных товаров')
                                ->value((int) Product::where('is_active', true)->count()),
                        ],
                        colSpan: 3,
                        adaptiveColSpan: 6
                    ),
                    Column::make(
                        [
                            ValueMetric::make('Категорий')
                                ->value((int) Category::count()),
                        ],
                        colSpan: 3,
                        adaptiveColSpan: 6
                    ),
                    Column::make(
                        [
                            ValueMetric::make('Пользователей')
                                ->value((int) User::count()),
                        ],
                        colSpan: 3,
                        adaptiveColSpan: 6
                    ),
                    Column::make(
                        [
                            ValueMetric::make('Заказов сегодня')
                                ->value((int) ($todayOrders?->total_orders ?? 0)),
                        ],
                        colSpan: 3,
                        adaptiveColSpan: 6
                    ),
                    Column::make(
                        [
                            ValueMetric::make('Выручка сегодня')
                                ->value((float) ($todayOrders?->total_revenue ?? 0))
                                ->valueFormat(fn (float $value): string => number_format($value, 2) . ' BYN'),
                        ],
                        colSpan: 3,
                        adaptiveColSpan: 6
                    ),
                ]),
            ]),

            Box::make('Быстрые действия', [
                Flex::make([
                    ActionButton::make('Создать товар', app(ProductResource::class)->getFormPageUrl())
                        ->primary()
                        ->icon('plus'),
                    ActionButton::make('Создать категорию', app(CategoryResource::class)->getFormPageUrl())
                        ->success()
                        ->icon('plus'),
                    ActionButton::make('Все товары', app(ProductResource::class)->getIndexPageUrl())
                        ->secondary()
                        ->icon('list-bullet'),
                    ActionButton::make('Все категории', app(CategoryResource::class)->getIndexPageUrl())
                        ->secondary()
                        ->icon('list-bullet'),
                ])->justifyAlign('start'),
            ]),
        ];
	}
}
