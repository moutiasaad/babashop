<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductOption extends Model
{
    protected $table = 'product_options';

    protected $fillable = ['product_id', 'name', 'values'];

    protected $casts = ['values' => 'array'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function optionValues()
    {
        return $this->hasMany(ProductOptionValue::class, 'product_option_id');
    }
}
