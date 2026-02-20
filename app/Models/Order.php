<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id','number','status','currency','subtotal','discount_amount',
        'shipping_amount','total','payment_method','delivery_method',
        'customer_name','customer_phone','customer_email','shipping_address',
    ];

    protected $casts = [
        'subtotal' => 'float',
        'discount_amount' => 'float',
        'shipping_amount' => 'float',
        'total' => 'float',
        'shipping_address' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
