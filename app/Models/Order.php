<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

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
        'total_price',
        'delivery_cost',
        'total_net_a_pay',
        'status',
        'is_paid',
        'is_rated',
        'is_shipped',
        'client_note',
        'payment_note',
        'admin_note',
        'start_date_delivery',
        'end_date_delivery',
        'merchant_id',
        'designAttributeIds',
        'designOptionIds',
        'card_description',
        'hide_buyer_identity',
    ];

    protected $casts = [
        'start_date_delivery' => 'datetime',
        'end_date_delivery' => 'datetime',
        'total_price' => 'decimal:2',
        'delivery_cost' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'is_paid' => 'boolean',
        'is_rated' => 'boolean',
        'is_shipped' => 'boolean',
        'status' => 'integer',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function orderLines()
    {
        return $this->hasMany(OrderLine::class);
    }

    // Status helper method
    public function getStatusTextAttribute()
    {
        $statuses = [
            0 => 'En attente',
            1 => 'Confirmée',
            2 => 'En préparation',
            3 => 'En livraison',
            4 => 'Livrée',
            5 => 'Annulée',
        ];

        return $statuses[$this->status] ?? 'Inconnu';
    }

    // Payment status helper
    public function getPaymentStatusTextAttribute()
    {
        return $this->is_paid ? 'Payée' : 'Non payée';
    }
}
