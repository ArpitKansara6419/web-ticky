<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppNotification extends Model
{
    // Explicitly define the table name
    protected $table = 'app_notifications';

    protected $fillable = [
        'engineer_id',
        'title',
        'body',
    ];
}
