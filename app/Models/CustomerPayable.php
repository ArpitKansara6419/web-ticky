<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerPayable extends Model
{
    use HasFactory;

    protected $table = 'customer_payables'; // Define your table name
    
    protected $fillable = [
        'ticket_work_id',
        'ticket_id',
        'engineer_id',
        'customer_id',
        'total_work_time',
        'work_start_date',
        'work_end_date',
        'work_start_time',
        'work_end_time',
        'hourly_rate',
        'halfday_rate',
        'fullday_rate',
        'monthly_rate',
        'hourly_payable',
        'overtime_payable',
        'client_payable',
        'overtime_hour',
        'travel_cost',
        'tool_cost',
        'ot_payable',
        'ooh_payable',
        'ww_payable',
        'hw_payable',
        'is_overtime',
        'is_holiday',
        'is_weekend',
        'is_out_of_office_hours',
        'total_payable',
        'currency',
        'note',
        'status',
        'payment_status', // Pending, Processing, Completed
    ];

    public function engineer()
    {
        return $this->belongsTo(Engineer::class, 'engineer_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function lead()
    {
        return $this->hasOne(Lead::class, 'customer_id', 'customer_id');
    }
    
    
    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    public function ticketWork()
    {
        return $this->belongsTo(TicketWork::class, 'ticket_work_id');
    }

    public function engCharge(){
        return $this->hasOne(EngineerCharge::class, 'engineer_id', 'engineer_id');
    }
}
