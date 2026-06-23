<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParserSite extends Model
{
    protected $fillable = ['name', 'code', 'domains', 'selectors', 'is_active'];

    protected $casts = [
        'domains' => 'array',
        'selectors' => 'array',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
