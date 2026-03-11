<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AttributeOption extends Model
{
    protected $fillable = [
        'attribute_id',
        'value',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class);
    }

    public function skus(): BelongsToMany
    {
        return $this->belongsToMany(Sku::class, 'attribute_option_sku');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(
            Product::class,
            'attribute_option_product'
        );
    }

    // Для MoonShine
    public function getLabelAttribute(): string
    {
        return $this->attribute?->name . ': ' . $this->value;
    }
}
