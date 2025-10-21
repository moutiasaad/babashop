<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;
    protected $fillable = ['title' , 'description', 'type', 'date','sent','type_receiver','users'];
    protected $table = 'notifications';

}
