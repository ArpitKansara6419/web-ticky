<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkBreak extends Model
{
    use HasFactory;

    protected $table = 'work_breaks';

    protected $fillable = [
        'engineer_id',
        'ticket_work_id',
        'ticket_id',
        'work_date',
        'break_start_date',
        'break_end_date',
        'engineer_id',
        'start_time',
        'end_time',
        'total_break_time',
        'status',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    // Timezone conversion accessors
    public function getWorkDateTimezoneAttribute()
    {
        return $this->work_date ? utcToTimezone($this->work_date, $this->ticket?->timezone) : null;
    }

    public function getBreakStartDateTimezoneAttribute()
    {
        return $this->break_start_date ? utcToTimezone($this->break_start_date, $this->ticket?->timezone) : null;
    }

    public function getBreakEndDateTimezoneAttribute()
    {
        return $this->break_end_date ? utcToTimezone($this->break_end_date, $this->ticket?->timezone) : null;
    }

    public function getStartTimeTimezoneAttribute()
    {
        return $this->start_time ? utcToTimezone($this->start_time, $this->ticket?->timezone) : null;
    }

    public function getEndTimeTimezoneAttribute()
    {
        return $this->end_time ? utcToTimezone($this->end_time, $this->ticket?->timezone) : null;
    }
}
