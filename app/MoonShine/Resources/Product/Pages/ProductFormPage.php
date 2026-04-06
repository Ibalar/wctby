<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Product\Pages;

use App\MoonShine\Resources\AttributeOption\AttributeOptionResource;
use App\MoonShine\Resources\Category\CategoryResource;
use App\MoonShine\Resources\Product\ProductResource;
use App\MoonShine\Resources\ProductAttributeOption\ProductAttributeOptionResource;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\FormBuilderContract;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Fields\Relationships\RelationRepeater;
use MoonShine\Laravel\Fields\Slug;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\Support\ListOf;
use MoonShine\TinyMce\Fields\TinyMce;
use MoonShine\UI\Components\FormBuilder;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Layout\Column;
use MoonShine\UI\Components\Layout\Grid;
use MoonShine\UI\Components\Tabs;
use MoonShine\UI\Components\Tabs\Tab;
use MoonShine\UI\Fields\Color;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Json;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Position;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;
use Throwable;

/**
 * @extends FormPage<ProductResource>
 */
class ProductFormPage extends FormPage
{
    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            Box::make([
                Tabs::make([
                    Tab::make('Основные данные', [
                        ID::make(),
                        Text::make('Название', 'name')->required(),

                        Grid::make([
                            Column::make([
                                Slug::make('Slug', 'slug')
                                    ->required()
                                    ->unique()
                                    ->from('name')
                                    ->live()
                                    ->locked()
                                    ->hint('Слаг формируется автоматически на основе названия товара после сохранения карточки.'),
                            ], colSpan: 6),
                            Column::make([
                                Text::make('SKU', 'sku')
                                    ->required()
                                    ->hint('Уникальный код товара / артикул'),
                            ], colSpan: 6),
                            Column::make([
                                BelongsTo::make('Категория', 'category', resource: CategoryResource::class)
                                    ->nullable()
                                    ->searchable()
                                    ->required(),
                            ], colSpan: 6),
                            Column::make([
                                Select::make('Тип товара', 'type')
                                    ->options([
                                        'simple' => 'Обычный',
                                        'bundle' => 'Комплект',
                                    ])
                                    ->default('simple'),
                            ], colSpan: 6),
                        ]),

                        Number::make('Базовая цена', 'base_price')->min(0)->step(0.01),
                        Json::make('Флаги', 'flags')
                            ->fields([
                                Position::make(),
                                Text::make('Текст метки', 'title')->default('Новинка'),
                                Color::make('Цвет текста', 'color_text')->default('#FFFFFF'),
                                Color::make('Цвет фона', 'color')->default('#2f6ed5'),
                                Switcher::make('Active'),
                            ])
                            ->nullable()
                            ->removable()
                            ->hint('Метки на карточке товара: Новинка, Хит продаж, Скидка и т.д.'),
                        Switcher::make('Активен', 'is_active')->default(true),
                        Switcher::make('Featured', 'featured')
                            ->default(false)
                            ->hint('Используется в витринных блоках каталога и других выделенных списках'),
                    ]),

                    Tab::make('Варианты', [
                        RelationRepeater::make('SKU / варианты', 'skus')
                            ->fields([
                                ID::make(),
                                Text::make('SKU', 'sku')->required(),
                                Number::make('Цена', 'price')->min(0)->step(0.01)->required(),
                                Number::make('Старая цена', 'old_price')->min(0)->step(0.01),
                                Number::make('Остаток', 'stock')->min(0)->required(),
                                Switcher::make('Активен', 'is_active')->default(true),
                            ])
                            ->creatable()
                            ->removable(),
                    ]),

                    Tab::make('Описание', [
                        Textarea::make('Краткое описание', 'short_description'),
                        TinyMce::make('Описание', 'description'),
                    ]),

                    Tab::make('Изображения', [
                        Image::make('Изображения', 'images')
                            ->multiple()
                            ->removable(),
                    ]),

                    Tab::make('SEO', [
                        Text::make('Meta title', 'meta_title')
                            ->hint('Если пусто — можно подставлять название товара на фронте'),
                        Textarea::make('Meta description', 'meta_description')
                            ->hint('Краткое описание 120–160 символов'),
                        Text::make('Meta keywords', 'meta_keywords')
                            ->hint('Ключевые слова через запятую'),
                    ]),

                    Tab::make('Характеристики', [
                        RelationRepeater::make(
                            'Характеристики',
                            'productAttributes',
                            resource: ProductAttributeOptionResource::class
                        )
                            ->fields([
                                BelongsTo::make(
                                    'Значение',
                                    'attributeOption',
                                    resource: AttributeOptionResource::class
                                )->searchable(),
                            ])
                            ->creatable()
                            ->removable(),
                        Json::make('Свойства', 'properties')
                            ->fields([
                                Position::make(),
                                Text::make('Название', 'name')->required(),
                                Text::make('Значение', 'value')->required(),
                                Number::make('Порядок', 'sort_order')->default(0),
                            ])
                            ->creatable()
                            ->removable()
                            ->nullable(),
                    ]),
                ])->vertical(),
            ]),
        ];
    }

    protected function buttons(): ListOf
    {
        return parent::buttons();
    }

    protected function formButtons(): ListOf
    {
        return parent::formButtons();
    }

    protected function rules(DataWrapperContract $item): array
    {
        $rules = [
            'sku' => [
                'required',
                'string',
                Rule::unique('products', 'sku')->ignore($item->id),
            ],
            'skus.*.sku' => [
                'required',
                'string',
            ],
        ];

        $skus = request('skus', []);

        foreach ($skus as $index => $row) {
            $id = Arr::get($row, 'id');
            $rules["skus.$index.sku"][] = Rule::unique('skus', 'sku')->ignore($id);
        }

        return $rules;
    }

    public function validationMessages(): array
    {
        return [
            'sku.unique' => 'Такой SKU уже используется у другого товара.',
            'skus.*.sku.unique' => 'Такой SKU уже используется у другого варианта.',
        ];
    }

    protected function modifyFormComponent(FormBuilderContract $component): FormBuilderContract
    {
        return $component;
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function topLayer(): array
    {
        return [
            ...parent::topLayer()
        ];
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function mainLayer(): array
    {
        return [
            ...parent::mainLayer()
        ];
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function bottomLayer(): array
    {
        return [
            ...parent::bottomLayer()
        ];
    }
}
