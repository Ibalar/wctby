<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id','item_type','item_id','name','sku','price','quantity','line_total','meta',
    ];

    protected $casts = [
        'price' => 'float',
        'line_total' => 'float',
        'meta' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
