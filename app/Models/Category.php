<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

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
            'image' => $this->getFirstMediaUrl('promo_image'),
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
