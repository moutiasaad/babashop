<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Order;
use App\Models\Product;

class Admin extends Authenticatable
{
    protected $fillable = ['name', 'title', 'email', 'password', 'role_id', 'image', 'merchant_id', 'phone'];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(UserPermission::class, 'permission_user', 'user_id', 'permission_id');
    }
    
public function getPendingOrdersCount()
{
    $query = Order::where('status', 2);

    if (isset($this->merchant_id)) {
        $query->where('merchant_id', $this->merchant_id);
    }

    return $query->count();
}
    public function getPendingProducts()
    {
        return Product::where('is_approved',0)
            ->where('deleted',0)
            ->count();
    }

    public function hasPermission($permissionName)
    {
        // Super Admin and Merchant both get full access
        if ($this->role_id == 1 || $this->role_id == 2) {
            return true;
        }

        return $this->permissions->contains('name', $permissionName);
    }

    // Method to check if the admin has a certain permission
}
