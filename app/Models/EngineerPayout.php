<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EngineerPayout extends Model
{
    use HasFactory;

    protected $table = 'engineer_payout';

    protected $fillable = [
        'engineer_id',
        'from_date',
        'to_date',
        'ticket_work_ids',
        'total_payable',
        'extra_incentive',
        'gross_pay',
        'currency',
        'note',
        'payment_date',
        'payment_status',
        'payment_type',
        'payout_type',
        'payout_month',
        'ZUS',
        'PIT',

    ];

    protected $casts = [
        'ticket_work_ids' => 'array',
        'from_date' => 'date',
        'to_date' => 'date',
        'payment_date' => 'date',
        'total_payable' => 'float',
        'extra_incentive' => 'float',
    ];

    public function engineer()
    {
        return $this->belongsTo(Engineer::class, 'engineer_id');
    }
    
}
