<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
       'job_type',
       'engineers',
       'notification_template',
       'notification_title',
       'notification_subtitle',
       'notification_text',
       'notification_interval',
       'time',
       'month',
       'weekday',
       'month',
       'day',
       'custom_date',
       'end_date',
    ];

}
