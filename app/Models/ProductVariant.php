<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $table    = 'product_variants';
    protected $fillable = ['product_id', 'options', 'qty', 'price', 'sku', 'image_path'];
    protected $casts    = ['options' => 'array', 'qty' => 'integer'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image_path ? url($this->image_path) : null;
    }

    /** True when every key/value in $selected matches this variant's options. */
    public function matchesSelection(array $selected): bool
    {
        $opts = $this->options ?? [];
        if (count($opts) !== count($selected)) return false;
        foreach ($selected as $k => $v) {
            if (!isset($opts[$k]) || (string)$opts[$k] !== (string)$v) return false;
        }
        return true;
    }
}
