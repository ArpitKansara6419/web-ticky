<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyLeaveBalance extends Model
{
    protected $fillable = ['engineer_id', 'year', 'month', 'allocated_leaves', 'used_leaves', 'carry_forward_leaves'];

    public function engineer()
    {
        return $this->belongsTo(User::class, 'engineer_id');
    }
    
}