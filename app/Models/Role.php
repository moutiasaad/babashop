<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'admin_roles';

    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * The admins that belong to this role.
     */
    public function admins()
    {
        return $this->hasMany(Admin::class, 'role_id');
    }
}
