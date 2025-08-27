<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EngineerLeave extends Model
{
    use HasFactory;

    protected $table = 'engineer_leaves';

    protected $fillable = [
        'engineer_id',
        'paid_from_date',
        'paid_to_date',
        'unpaid_from_date',
        'unpaid_to_date',
        'paid_leave_days',
        'unpaid_leave_days',
        'unpaid_reason',
        'note',
        'status',
        'leave_approve_status',
        'leave_type',
        'signed_paid_document',
        'unsigned_paid_document',
        'signed_unpaid_document',
        'unsigned_unpaid_document',

    ];

    public function engineer()
    {
        return $this->belongsTo(Engineer::class, 'engineer_id');
    }
}
