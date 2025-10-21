<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\Client;
use Carbon\Carbon;

class Balance extends Model
{
    use  HasFactory;

    public $table = 'balances';

    protected $dates = [
        'created_at',
    ];

    protected $fillable = [
        'amount',
        'merchant_id',
    ];
}
