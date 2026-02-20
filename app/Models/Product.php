<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'category_id','type','name','slug','sku','short_description','images',
        'description','base_price','flags','is_active',
        'meta_title','meta_description','meta_keywords',
    ];

    protected $casts = [
        'flags' => 'array',
        'is_active' => 'boolean',
        'images' => 'array',
    ];

    public function properties()
    {
        return $this->hasMany(ProductProperty::class);
    }

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

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->useDisk('public')
            ->singleFile(false);
    }
}
