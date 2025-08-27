<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EngineerDailyWorkExpense extends Model
{

    use HasFactory;

    protected $table = 'engineer_daily_work_expense';

    protected $fillable = [
        'ticket_work_id',
        'engineer_id',
        'ticket_id',
        'name',
        'document',
        'expense',
        'status',
    ];

    /**
     * Relationship with Engineer (Example: assuming an Engineer model exists)
     */

    public function engineer()
    {
        return $this->belongsTo(Engineer::class, 'engineer_id');
    }

    /**
     * Relationship with Ticket (Example: assuming a Ticket model exists)
     */

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

}
