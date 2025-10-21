<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Attachment;
use App\Models\Balance;


class Merchant extends Model
{
    protected $fillable = [
        'brand_name',
        'image',
        'type_merchant_id',
        'street_name',
        'description',
        'notif_email',
        'latitude',
        'longitude',
        'delivery_is_free',
        'attribut_reduction',
        'attribut_delivery',
        'platform_fee',
        'price_free_delivery',
        'open_at',
        'close_at',
        'contract_file',
        'visibility',
        'deleted',
        'order_item',
        'return_policy',
        'rating',
        'categories'
    ];
    protected $appends = ['is_open', 'attributes', 'is_disabled' , 'star','delivery_day','formatted_categories'];
    
    protected $casts = [
        'categories' => 'array',
    ];

     protected $table = 'merchant';
     use HasFactory;

    public function type()
    {
        return $this->belongsTo(TypeMerchant::class, 'type_merchant_id');
    }
public function getDeliveryDayAttribute()
{
    if ($this->attribut_delivery === 'اليوم') {
        return 1;
    } elseif ($this->attribut_delivery === 'يومين') {
        return 2;
    } elseif ($this->attribut_delivery === 'ثلاث ايام') {
        return 3;
    }

    return 1; // default if not matched
}
public function getFormattedCategories(): string
{
    if (!is_array($this->categories) || empty($this->categories)) {
        return '';
    }

    $names = MerchantCategorie::whereIn('id', $this->categories)->pluck('name')->toArray();

    return implode(' - ', $names);
}
public function getFormattedCategoriesAttribute(): string
{
    return $this->getFormattedCategories();
}

    // public function country()
    // {
    //     return $this->belongsTo(Country::class);
    // }

  
    public function balance()
    {
        return $this->hasOne(Balance::class, 'merchant_id');
    }
    
   
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function deliveryTimes()
    {
        return $this->hasMany(DeliveryTime::class , 'merchant_id');
    }
    
      public function getStarAttribute()
        {
            $average = DB::table('ratings')
            ->where('marchant_id', $this->id) // Use the correct foreign key name (e.g., merchant_id)
                ->avg('star'); // Calculate the average of the 'star' column
    
    
            return round($average ?? 0);
        }
    
    public function getIsOpenAttribute()
    {
        if ($this->type_merchant_id == 2) {
        return true;
        }
        $now = Carbon::now('Asia/Riyadh');
        $openTime = Carbon::createFromTimeString($this->open_at, 'Asia/Riyadh');
        $closeTime = Carbon::createFromTimeString($this->close_at, 'Asia/Riyadh');
    
        // Adjust for merchants that close after midnight
        if ($closeTime->lessThan($openTime)) {
            $closeTime->addDay();
        }
    
        return $now->between($openTime, $closeTime);
    }
        public function getIsDisabledAttribute()
    {
        return false;
    }

        public function getAttributesAttribute()
    {
        $attributes = [];

        // Add stars attribute for specific merchants
        $attributes['stars'] = $this->rating ?? '- (0)';

        // Add discount attribute if the merchant offers a discount
        if ($this->attribut_reduction) {
            $attributes['discount'] = "%" . $this->attribut_reduction . ' خصم ';
        }

        // Add delivery attribute for merchants with free delivery
        if ($this->attribut_delivery) {
            $attributes['delivery'] = 'التوصيل ' . $this->attribut_delivery;
        }

        // Add custom attribute for merchants based on another condition
        if ($this->delivery_is_free == 1) {
            $attributes['custom'] = 'التوصيل مجاني';
        }

        return $attributes;
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'file', 'id')
                    ->where('attachmentable_type', 'merchant');
    }

    public function zones()
    {
        return $this->belongsToMany(Zone::class, 'merchant_zone', 'merchant_id', 'zone_id')
                    ->withTimestamps();
    }

    public function getImageAttribute($value)
    {
        return env('FILES_CDN') . $value;
    }
}
