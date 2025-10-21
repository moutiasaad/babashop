<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order_lines extends Model
{
    protected $table = 'order_lines';

    protected $fillable = [
        'product_id',
        'user_id',
        'order_id',
        'unit_price',
        'price',
        'qty',
        'card_description',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function order()
    {
        return $this->belongsTo(Orders::class);
    }
}
