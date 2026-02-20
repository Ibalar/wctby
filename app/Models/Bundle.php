<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Bundle extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = ['name','slug','description','total_price','is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function items()
    {
        return $this->hasMany(BundleItem::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'bundle_items');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->useDisk('public');
    }
}
