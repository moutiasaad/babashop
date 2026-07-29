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
        'delivery_zone_id',
        'delivery_fee_snapshot',
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

    public function deliveryZone()
    {
        return $this->belongsTo(DeliveryZone::class, 'delivery_zone_id');
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
        // Aligned with admin dashboard "Modifier le statut" dropdown
        $statuses = [
            0 => 'En attente',
            1 => 'Confirmée',
            2 => 'En préparation',
            3 => 'En livraison',
            4 => 'Livrée',
            5 => 'Annulée',
        ];

        return $statuses[$value] ?? 'Inconnu';
    }

    public function getStatusColorAttribute()
    {
        // Aligned with admin dashboard getStatusBadge() colors
        $statusColors = [
            0 => '#6C757D', // bg-secondary – En attente
            1 => '#17A2B8', // bg-info      – Confirmée
            2 => '#0275D8', // bg-primary   – En préparation
            3 => '#FF9800', // orange       – En livraison
            4 => '#28A745', // bg-success   – Livrée
            5 => '#DC3545', // bg-danger    – Annulée
        ];

        $rawStatus = isset($this->attributes['status']) ? (int)$this->attributes['status'] : null;
        return $statusColors[$rawStatus] ?? '#6C757D';
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
