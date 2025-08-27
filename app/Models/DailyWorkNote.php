<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyWorkNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_id',
        'note',
        'documents',
        'status',
    ];

    public function work()
    {
        return $this->belongsTo(TicketWork::class, 'work_id', 'id');
    }
}
