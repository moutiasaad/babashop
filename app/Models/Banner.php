<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'link',
        'order_item',
        'visibility',
        'deleted',
    ];

    protected $casts = [
        'visibility' => 'boolean',
        'deleted' => 'boolean',
        'order_item' => 'integer',
    ];
}
