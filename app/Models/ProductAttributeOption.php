<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductAttributeOption extends Model
{
    protected $table = 'attribute_option_product';
    public $timestamps = false;
    protected $fillable = ['product_id', 'attribute_option_id'];

    // Виртуальное поле для RelationRepeater
    protected $appends = ['attribute_id'];

    public function attributeOption()
    {
        return $this->belongsTo(AttributeOption::class, 'attribute_option_id');
    }

    public function getAttributeIdAttribute()
    {
        return $this->attributeOption?->attribute_id;
    }

}
