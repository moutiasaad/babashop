<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Attachment extends Model
{
    use  HasFactory;

    public $table = 'attachments';

    protected $dates = [
        'updated_at',
        'created_at',
    ];
    protected $fillable = [
        'name',
        'file',
        'file_type',
        'folder',
        'attachmentable_type',
        'updated_at'
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
