<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'category_id','type','name','slug','sku','short_description','images',
        'description','base_price','flags','properties','is_active',
        'meta_title','meta_description','meta_keywords',
    ];

    protected $casts = [
        'flags' => 'array',
        'properties' => 'array',
        'is_active' => 'boolean',
        'images' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function skus()
    {
        return $this->hasMany(Sku::class);
    }

    public function bundles()
    {
        return $this->belongsToMany(Bundle::class, 'bundle_items');
    }

    public function attributeOptions(): BelongsToMany
    {
        return $this->belongsToMany(
            AttributeOption::class,
            'attribute_option_product'
        )->with('attribute');
    }

    public function productAttributes()
    {
        return $this->hasMany(ProductAttributeOption::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->useDisk('public')
            ->singleFile(false);
    }
}
