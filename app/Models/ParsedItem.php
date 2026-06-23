<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParsedItem extends Model
{
    protected $fillable = [
        'source_url', 'site_code', 'status', 'raw_data', 'product_id', 'error_message',
    ];

    protected $casts = [
        'raw_data' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
