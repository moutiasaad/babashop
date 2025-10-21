<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'discount_type',
        'discount',
        'expires_at',
        'use_limit',
        'per_user_limit',
        'allowed_categories',
        'min_order_amount',
        'max_discount',
        'active'
    ];
    
    protected $casts = [
        'allowed_categories' => 'array',
        'expires_at' => 'datetime',
    ];
    
    // Optionally, you can add any relationships here if needed, like related orders.
}
