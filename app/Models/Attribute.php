<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attribute extends Model
{
    protected $fillable = [
        'name',
        'code',
        'type',
        'is_filterable',
        'sort_order',
    ];

    protected $casts = [
        'is_filterable' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function options(): HasMany
    {
        return $this->hasMany(AttributeOption::class);
    }
}
