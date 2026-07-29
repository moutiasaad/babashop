<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPermission extends Model
{
    protected $table = 'user_permissions';

    protected $fillable = [
        'name',
        'text',
        'groupe',
    ];

    /**
     * The admins that have this permission.
     */
    public function admins()
    {
        return $this->belongsToMany(Admin::class, 'permission_user', 'permission_id', 'user_id');
    }
}
