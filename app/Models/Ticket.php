<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_code',
        'customer_id',
        'lead_id',
        'client_name',
        'client_address',
        'task_start_date',
        'task_end_date',
        'task_time',
        'task_name',
        'scope_of_work',
        'tools',
        'poc_details',
        're_details',
        'call_invites',
        'ref_sign_sheet',
        'documents', 
        'engineer_id',
        'rate_type',
        'standard_rate',
        'travel_cost',
        'tool_cost',
        'food_expenses', 
        'misc_expenses',
        'is_engineer_agreed_rate',
        'engineer_agreed_rate',
        'currency_type',
        'engineer_status',
        'status', // inprogress, hold, 
        'apartment',
        'add_line_1',
        'add_line_2',
        'city',
        'country',
        'timezone',
        'zipcode',
        'offered_notification_date_time',
        'latitude',
        'longitude',
    ];

    protected $appends = [
        'ticket_start_date_time', 
        'ticket_end_date_time',
        'ticket_start_date_tz',
        'ticket_end_date_tz',
        'ticket_time_tz',
    ];

    public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function engineer(){
        return $this->belongsTo(Engineer::class, 'engineer_id');
    }

    public function ticketWork () {
        return $this->hasMany(TicketWork::class, 'ticket_id');
    }

    public function lead(){
        return $this->belongsTo(Lead::class, 'lead_id');
    }

    public function engCharge(){
        return $this->hasOne(EngineerCharge::class, 'engineer_id', 'engineer_id');
    }

    public function getTicketStartDateTimeAttribute()
    {
        return $this->task_start_date. ' ' . $this->task_time;
    }

    public function getTicketEndDateTimeAttribute()
    {
        return $this->task_end_date. ' ' . $this->task_time;
    }

    public function getTicketStartDateTzAttribute()
    {
        return utcToTimezone($this->ticket_start_date_time, $this->timezone)->format('Y-m-d');
    }

    public function getTicketEndDateTzAttribute()
    {
        return utcToTimezone($this->ticket_end_date_time, $this->timezone)->format('Y-m-d');
    }

    public function getTicketTimeTzAttribute()
    {
        return utcToTimezone($this->ticket_start_date_time, $this->timezone)->format('H:i:s');
    }

}
