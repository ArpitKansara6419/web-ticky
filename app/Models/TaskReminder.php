<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskReminder extends Model
{
    use HasFactory;

    protected $table = 'task_reminders';

    protected $fillable = [
        'ticket_id',
        'customer_id',
        'engineer_id',
        'task_type',
        'work_reminder_for',
        'reminder_at',
        'user_response',
        'reason'
    ];
}
