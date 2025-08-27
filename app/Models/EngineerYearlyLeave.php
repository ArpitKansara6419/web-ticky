<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EngineerYearlyLeave extends Model
{
    use HasFactory;
    protected $table = 'engineer_yearly_leaves';
    protected $fillable = [
        'engineer_id',
        'year',
        'month',
        'balance',
    ];
}
