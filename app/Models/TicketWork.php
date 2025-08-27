<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

    class TicketWork extends Model
    {

        use HasFactory;
        protected $table = 'ticket_daily_work';
        protected $fillable = [
            'ticket_id',
            'user_id',
            'work_start_date',  
            'work_end_date',
            'start_time',
            'end_time',
            'total_work_time',
            'hourly_rate',
            'halfday_rate',
            'fullday_rate',
            'monthly_rate',
            'hourly_payable',
            'overtime_payable',
            'weekend_payable',
            'holiday_payable',
            'is_overtime', // 0 - NO, 1 - Yes
            'is_overtime_approved', // 0 - NO, 1 - YES
            'is_holiday', // 0 - NO, 1 - Yes
            'is_weekend', // 0 - NO, 1 - Yes
            'is_out_of_office_hours', // 0 - NO, 1 - Yes
            'out_of_office_duration',
            'out_of_office_payable',
            'document_file',
            'overtime_hour',
            'travel_cost',
            'tool_cost',
            'food_cost',
            'other_pay',
            'currency',
            'daily_gross_pay',
            'note',
            'location',
            'address',
            'engineer_payout_status',
            'client_payment_status',
            'status',
        ];

        protected $appends = [  
            'work_start_date_timezone',
            'work_end_date_timezone',
            'start_time_timezone',
            'end_time_timezone',
        ];

        public function engineer(){
            return $this->belongsTo(Engineer::class, 'user_id');
        }

        public function ticket(){
            return $this->belongsTo(Ticket::class, 'ticket_id');
        }

        public function breaks() {
            return $this->hasMany(WorkBreak::class, 'ticket_work_id')->orderBy('work_breaks.created_at', 'DESC');
        }

        public function workExpense() {
            return $this->hasMany(EngineerDailyWorkExpense::class, 'ticket_work_id');
        }

        public function workNote() {
            return $this->hasMany(DailyWorkNote::class, 'work_id');
        }

        public function engCharge(){
            return $this->hasOne(EngineerCharge::class, 'engineer_id', 'user_id');
        }

        // Timezone conversion accessors
        public function getWorkStartDateTimezoneAttribute()
        {
            return $this->work_start_date ? utcToTimezone($this->work_start_date, $this->ticket?->timezone) : null;
        }

        public function getWorkEndDateTimezoneAttribute()
        {
            return $this->work_end_date ? utcToTimezone($this->work_end_date, $this->ticket?->timezone) : null;
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
