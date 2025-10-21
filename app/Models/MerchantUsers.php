<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MerchantUsers extends Model
{
    protected $fillable = [
        'name',
        'password',
        'email',
        'phone',
        'merchant_id',
        'role_id',
        'image'
    ];
    protected $table = 'admins';

    public function merchant()
    {
        return $this->belongsTo(Merchant::class, 'merchant_id');
    }
}
