<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MerchantCategorie extends Model
{
    use HasFactory;

    protected $table = 'merchant_categories';

    protected $fillable = ['name'];

}
