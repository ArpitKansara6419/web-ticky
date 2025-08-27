<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EngineerCharge extends Model
{
    use HasFactory;

    protected $fillable = [
        'engineer_id',
        'hourly_charge',
        'half_day_charge',
        'full_day_charge',
        'monthly_charge',
        'check_in_time',
        'check_out_time',
        'annual_leaves',
        'accumulated_leaves',
        'currency_type',
    ];

    protected $appends = [
        'check_in_tz', 
        'check_out_tz',
    ];

    public function engineer(){
       return $this->belongsTo(Engineer::class); 
    }

    public function getCheckInTzAttribute()
    {
        return utcToTimezone($this->check_in_time, $this->engineer->timezone)->format('H:i:s');
    }

    public function getCheckOutTzAttribute()
    {
        return utcToTimezone($this->check_out_time, $this->engineer->timezone)->format('H:i:s');
    }

}
