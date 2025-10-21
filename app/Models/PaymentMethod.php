<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $table = 'payment_methods';

    protected $fillable = [
        'name',
        'code',
        'description',
        'is_active',
        'logo',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
