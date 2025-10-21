<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NotificationTemplate extends Model
{
    use HasFactory;
    protected $fillable = ['title' , 'description', 'type','type_lable'];
    protected $table = 'notification_templates';


}
