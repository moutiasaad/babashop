<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlists extends Model
{
    protected $fillable = ['user_id' , 'product_id'];
    protected $table = 'wishlists';
    public function product()
    {
        return $this->belongsTo(Product::class , 'product_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class , 'user_id');
    }
}
