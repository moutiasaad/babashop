<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_ar',
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

    protected $appends = ['localized_name'];

    public function getLocalizedNameAttribute(): ?string
    {
        return app()->getLocale() === 'ar' && !empty($this->name_ar)
            ? $this->name_ar
            : $this->name;
    }
}
