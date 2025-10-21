<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Orders;
use App\Models\Product;

class Admin extends Authenticatable
{
    protected $fillable = ['name', 'email', 'password', 'role_id', 'image'];

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
    $query = Orders::where('status', 2);

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

        if ($this->role_id == 2) {
            return true;
        } else {
            return $this->permissions->contains('name', $permissionName);
        }
    }

    // Method to check if the admin has a certain permission
}
