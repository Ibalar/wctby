<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Slide extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'title',
        'subtitle',
        'link',
        'link_text',
        'image',
        'position',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'position' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('position');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->useDisk('public')
            ->singleFile();
    }
}
