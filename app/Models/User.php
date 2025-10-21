<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'users';
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable , HasApiTokens;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fullname',
        'email',
        'phone',
        'fcm_token',
        'address',
        'longitude',
        'latitude',
        'birth_date',
        'order_rate',
        'cart_notif_date',
        'image'
    ];

    public function wishlist()
{
    return $this->belongsToMany(Product::class, 'wishlists');
}

  public function getImageAttribute($value)
    {
        if($value)
            return env('FILES_CDN') . 'uploads/user_img/' . $value;
        else 
            return  $value;

    }

  

   
}
