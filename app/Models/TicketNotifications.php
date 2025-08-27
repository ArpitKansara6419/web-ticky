<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketNotifications extends Model
{
    use HasFactory;

    protected $table = 'ticket_notifications';

    protected $fillable = [
     'ticket_id',
     'date',
     'time',
     'notification_text',
     'engineer_id',
     'engineer_device_token',
     'notification_type',
     'is_repeat',
     'status',
     'last_send_time',
     'next_send_time',
    ];

    public function engineer(){
        return $this->belongsTo(Engineer::class, 'engineer_id');
    }
}
