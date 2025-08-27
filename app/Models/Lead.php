<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'lead_code',
        'customer_id',
        'lead_type',
        'end_client_name',
        'client_ticket_no',
        'task_start_date',
        'task_end_date',
        'task_time',
        'task_location',
        'scope_of_work',
        'rate_type',
        'hourly_rate',
        'half_day_rate',
        'full_day_rate',
        'monthly_rate',
        'currency_type',
        'lead_status',
        'reschedule_date',
        'travel_cost',
        'tool_cost',
        'apartment',
        'add_line_1',
        'add_line_2',
        'zipcode',
        'city',
        'country',
        'timezone',
        'is_ticket_created',
        'latitude',
        'longitude',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    
    public function ticket()
    {
        return $this->hasOne(Ticket::class, 'lead_id', 'id'); // Lead has one Ticket
    }
}
