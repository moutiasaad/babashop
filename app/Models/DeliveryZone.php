<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryZone extends Model
{
    protected $fillable = [
        'name',
        'name_ar',
        'code',
        'delivery_fee',
        'free_shipping_threshold',
        'estimated_days_min',
        'estimated_days_max',
        'is_active',
        'display_order',
    ];

    protected $casts = [
        'delivery_fee'             => 'decimal:3',
        'free_shipping_threshold'  => 'decimal:3',
        'is_active'                => 'boolean',
        'display_order'            => 'integer',
        'estimated_days_min'       => 'integer',
        'estimated_days_max'       => 'integer',
    ];

    public function orders()
    {
        return $this->hasMany(Orders::class, 'delivery_zone_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('display_order');
    }
}
