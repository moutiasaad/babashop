<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Models\Attachment;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\ProductOption;
use App\Models\ProductVariant;

class Product extends Model
{
    protected $table = 'product';

    protected $fillable = [
        'name', 'name_ar', 'image', 'description', 'description_ar',
        'type', 'sku', 'category_id', 'qty',
        'merchant_id', 'price', 'discount_price', 'discount_start',
        'discount_end', 'product_type', 'other_categories', 'visibility', 'deleted','is_approved'
    ];

    protected $casts = [
        'other_categories' => 'array',
    ];

    protected $appends = ['is_wishlisted', 'localized_name', 'localized_description'];

    public function getLocalizedNameAttribute(): ?string
    {
        return app()->getLocale() === 'ar' && !empty($this->name_ar)
            ? $this->name_ar
            : $this->name;
    }

    public function getLocalizedDescriptionAttribute(): ?string
    {
        return app()->getLocale() === 'ar' && !empty($this->description_ar)
            ? $this->description_ar
            : $this->description;
    }

    public function otherCategories()
    {
        return $this->belongsToMany(Category::class, 'category_product', 'product_id', 'category_id');
    }

    public function merchant()
    {
        return $this->belongsTo(Merchant::class, 'merchant_id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function wishlistedByUsers()
    {
        return $this->belongsToMany(User::class, 'wishlists');
    }

    public function getIsWishlistedAttribute()
    {
        $user = Auth::user();
        return $user ? $this->wishlistedByUsers()->where('user_id', $user->id)->exists() : false;
    }

    public function options()
    {
        return $this->hasMany(ProductOption::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'file', 'id')
                    ->where('attachmentable_type', 'products');
    }

    public function getImageAttribute($value)
    {
        if ($this->attachments->isNotEmpty()) {
            return $this->attachments->map(function ($attachment) {
                return env('FILES_CDN')."/uploads/" . $attachment->folder . '/' . $attachment->name;
            })->toArray();
        }
        if (!$value) return [];

        // If the stored value is already a full URL (external CDN like
        // LoremFlickr / Unsplash / a brand shop), return it unchanged.
        // Blindly prepending FILES_CDN would break the URL.
        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return [$value];
        }

        $image = env('FILES_CDN') . $value;
        return [$image];
    }
}
