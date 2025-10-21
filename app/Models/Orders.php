<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Order_lines;
use App\Models\DesignAttribute;
use App\Models\DesignOption;
use App\Models\Merchant;
use App\Observers\OrderObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
 
#[ObservedBy([OrderObserver::class])]
class Orders extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'coupon_id',
        'payment_method_id',
        'address',
        'latitude',
        'longitude',
        'phone',
        'fullname',
        'unit_price',
        'quantity',
        'total_price',
        'delivery_cost',
        'total_net_a_pay',
        'status',
        'is_paid',
        'is_shipped',
        'client_note',
        'admin_note',
        'driver_id',
        'start_date_delivery',
        'end_date_delivery',
        'merchant_id',
        'designAttributeIds',
        'designOptionIds',
        'card_description',
        'is_rated',
        'payment_note',
        'hide_buyer_identity',
        
    ];
protected $appends = ['status_color','products'];

    public function order_lines()
    {
        return $this->hasMany(Order_lines::class, 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function getProductsAttribute()
    {
        // 1) collect from lines
        $lines = $this->order_lines
                      ->map(fn($line) => $line->product ? $line->product->toArray() : null)
                      ->filter()  // drop nulls
                      ->toArray();

        // 2) include the old product_id if set
        if ($this->product) {
            $ids = array_column($lines, 'id');
            if (! in_array($this->product->id, $ids)) {
                $lines[] = $this->product->toArray();
            }
        }

        return $lines;
    }

    public function merchant()
    {
        return $this->belongsTo(Merchant::class, 'merchant_id');
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    public function getStatusAttribute($value)
    {
        $statuses = [
            0 => 'بانتظار المراجعة',
            1 => 'تم قبول الطلب',
            2 => 'تحت التجهيز',
            3 => 'تم الاستلام',
            4 => 'ملغاة',
            5 => 'مسترجعة',
            6 => 'بإنتضار العنوان',
        ];
    
        return $statuses[$value] ?? '-';
    }
    
    public function getStatusColorAttribute()
    {
        $statusColors = [
            0 => '#F0AD4E', // Yellow-ish
            1 => '#5BC0DE', // Blue-ish
            2 => '#0275D8', // Dark Blue
            3 => '#5CB85C', // Green
            4 => '#D9534F', // Red
            5 => '#F7A35C', // Orange
            5 => '#0275D8', // Orange
        ];
    
        // Ensure we retrieve the raw status value as an integer.
        $rawStatus = isset($this->attributes['status']) ? (int)$this->attributes['status'] : null;
        return $statusColors[$rawStatus] ?? '#000000';
    }

    public function getDesignAttributes()
    {
        $ids = json_decode($this->designAttributeIds, true);

        if (!is_array($ids) || empty($ids)) {
            return collect(); // Return an empty collection if no attributes
        }

        return DesignAttribute::whereIn('id', $ids)->get();
    }

    public function getDesignOptions()
    {
        $ids = json_decode($this->designOptionIds, true);

        if (!is_array($ids) || empty($ids)) {
            return collect(); // Return an empty collection if no options
        }

        return DesignOption::whereIn('id', $ids)->get();
    }

    public function getCardDescriptionAttribute()
    {
        $cardDescription = json_decode($this->attributes['card_description'], true);

        return is_array($cardDescription) ? $cardDescription : [
            'from' => '-',
            'to' => '-',
            'link' => '-',
            'message' => '-',
        ]; // Return default values if null or invalid
    }
}
