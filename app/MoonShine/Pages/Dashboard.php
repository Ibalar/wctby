<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\MoonShine\Resources\Category\CategoryResource;
use App\MoonShine\Resources\Product\ProductResource;
use Illuminate\Support\Facades\Cache;
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
        $metrics = Cache::remember('dashboard.metrics', 300, function () {
            $todayOrders = Order::query()
                ->whereDate('created_at', today())
                ->selectRaw('COUNT(*) as total_orders, COALESCE(SUM(total), 0) as total_revenue')
                ->first();

            return [
                'products_total' => (int) Product::count(),
                'products_active' => (int) Product::where('is_active', true)->count(),
                'categories' => (int) Category::count(),
                'users' => (int) User::count(),
                'orders_today' => (int) ($todayOrders?->total_orders ?? 0),
                'revenue_today' => (float) ($todayOrders?->total_revenue ?? 0),
            ];
        });

        return [


            Box::make('Основные метрики', [
                Grid::make([
                    Column::make(
                        [
                            ValueMetric::make('Товаров')
                                ->value($metrics['products_total']),
                        ],
                        colSpan: 3,
                        adaptiveColSpan: 6
                    ),
                    Column::make(
                        [
                            ValueMetric::make('Активных товаров')
                                ->value($metrics['products_active']),
                        ],
                        colSpan: 3,
                        adaptiveColSpan: 6
                    ),
                    Column::make(
                        [
                            ValueMetric::make('Категорий')
                                ->value($metrics['categories']),
                        ],
                        colSpan: 3,
                        adaptiveColSpan: 6
                    ),
                    Column::make(
                        [
                            ValueMetric::make('Пользователей')
                                ->value($metrics['users']),
                        ],
                        colSpan: 3,
                        adaptiveColSpan: 6
                    ),
                    Column::make(
                        [
                            ValueMetric::make('Заказов сегодня')
                                ->value($metrics['orders_today']),
                        ],
                        colSpan: 3,
                        adaptiveColSpan: 6
                    ),
                    Column::make(
                        [
                            ValueMetric::make('Выручка сегодня')
                                ->value($metrics['revenue_today'])
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
