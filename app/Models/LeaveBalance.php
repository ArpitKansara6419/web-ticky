<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveBalance extends Model
{

    protected $fillable = [
        'engineer_id', 
        'total_leaves', 
        'used_leaves', 
        'balance',
        'leave_credited_this_month',
        'total_yearly_alloted',
        'total_paid_leave_used',
        'total_unpaid_leave_used',
        'opening_balance_from_past_year',
        'freezed_leave_balance'
    ];

    public function engineer()
    {
        return $this->belongsTo(Engineer::class, 'engineer_id');
    }

    public function monthlyLeaves()
    {
        return $this->hasMany(MonthlyLeaveBalance::class, 'engineer_id');
    }
    
}
