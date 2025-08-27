<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EngineerExtraPay extends Model
{
    use HasFactory;
    protected $fillable = [
        'engineer_id',
        'overtime',
        'out_of_office_hour',
        'weekend',
        'public_holiday',
        'status ',
    ];
}
