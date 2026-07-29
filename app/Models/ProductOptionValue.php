<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductOptionValue extends Model
{
    protected $table = 'product_option_values';

    protected $fillable = ['product_option_id', 'value', 'qty', 'image_path'];

    protected $casts = ['qty' => 'integer'];

    public function option()
    {
        return $this->belongsTo(ProductOption::class, 'product_option_id');
    }

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image_path) return null;
        return url($this->image_path);
    }
}
