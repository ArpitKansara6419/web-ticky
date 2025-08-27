<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EngineerNotification extends Model
{
    use HasFactory;

    protected $table = 'engineer_notifications';

    protected $fillable = [
        'engineer_id',
        'title',
        'body',
        'notification_type',
        'response_additional_data',
        'seen_at',
        'is_seen'
    ];

    protected $casts = [
        'response_additional_data' => 'array'
    ];
}
