<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carts extends Model
{
    protected $fillable = [
        'product_id',
        'user_id',
        'qte',
        'card_description',
        'selected_options',
    ];

    protected $casts = [
        'selected_options' => 'array',
    ];
    protected $table = 'carts';
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
