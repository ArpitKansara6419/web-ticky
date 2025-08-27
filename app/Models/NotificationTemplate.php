<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
       'template_name',
       'title',
       'sub_title',
       'description',
       'type',
       'slug',
       'status',
    ];

}
