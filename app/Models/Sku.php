<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sku extends Model
{
    protected $fillable = ['product_id','sku','price','old_price','stock','is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function attributeOptions()
    {
        return $this->belongsToMany(AttributeOption::class, 'attribute_option_sku');
    }
}
