<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
     use HasFactory;
     protected $table = 'category';

    protected $fillable = ['name', 'image', 'merchant','order_item','visibility','deleted'];

    public function getImageAttribute($value)
    {
        return env('FILES_CDN') . $value;
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Many-to-many: Categories can have many subcategories
    public function subcategories()
    {
        return $this->belongsToMany(
            Category::class,
            'category_subcategory',
            'category_id',
            'subcategory_id'
        )->where('visibility', 1)->orderBy('order_item');
    }

    // Many-to-many: Subcategories can belong to many parent categories
    public function parentCategories()
    {
        return $this->belongsToMany(
            Category::class,
            'category_subcategory',
            'subcategory_id',
            'category_id'
        )->where('visibility', 1)->orderBy('order_item');
    }
}
