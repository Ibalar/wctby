<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Support\Facades\Storage;

class Category extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'description',
        'sort_order',
        'is_active',
        'promo_active',
        'promo_title',
        'promo_subtitle',
        'promo_image',
        'promo_button_text',
        'promo_link',
        'promo_product_id',
        'promo_badge_text',
        'promo_badge_color',
        'promo_old_price',
        'promo_new_price',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'promo_active' => 'boolean',
    ];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Получить всех потомков рекурсивно (включая текущую категорию)
     */
    public function descendants(): \Illuminate\Support\Collection
    {
        $descendants = collect([$this]);

        foreach ($this->children as $child) {
            $descendants = $descendants->merge($child->descendants());
        }

        return $descendants;
    }

    /**
     * Получить ID всех потомков (включая текущую категорию)
     */
    public function getDescendantIds(): array
    {
        return $this->descendants()->pluck('id')->toArray();
    }

    /**
     * Получить все товары из текущей категории и всех потомков
     */
    public function allProducts()
    {
        $categoryIds = $this->getDescendantIds();

        return Product::whereIn('category_id', $categoryIds)
            ->where('is_active', true);
    }

    /**
     * Scope для загрузки потомков
     */
    public function scopeWithDescendants($query)
    {
        return $query->with('children.children');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function promoProduct()
    {
        return $this->belongsTo(Product::class, 'promo_product_id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->useDisk('public');

        $this->addMediaCollection('promo_image')
            ->useDisk('public')
            ->singleFile();
    }

    public function getPromoDataAttribute()
    {
        if (!$this->promo_active) {
            return null;
        }

        $data = [
            'title' => $this->promo_title ?? 'WCT.BY ' . $this->name,
            'subtitle' => $this->promo_subtitle ?? 'Смотреть все',
            'button_text' => $this->promo_button_text ?? 'Подробнее',
            'badge_text' => $this->promo_badge_text,
            'badge_color' => $this->promo_badge_color,
            'old_price' => $this->promo_old_price,
            'new_price' => $this->promo_new_price,
            // Используем поле promo_image из БД
            'image' => $this->promo_image
                ? (str_starts_with($this->promo_image, 'http')
                    ? $this->promo_image
                    : Storage::url($this->promo_image))
                : null,
        ];

        // Определяем ссылку
        if ($this->promo_product_id && $this->promoProduct) {
            $data['link'] = route('catalog.product', $this->promoProduct->slug);
        } elseif ($this->promo_link) {
            $data['link'] = $this->promo_link;
        } else {
            $data['link'] = route('catalog.category', $this->slug);
        }

        return $data;
    }
}
