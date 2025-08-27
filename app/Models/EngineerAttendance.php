<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EngineerAttendance extends Model
{
    use HasFactory;

    protected $table = 'engineer_attendance';

    protected $fillable = [
        'user_id',
        'attendance_date',
        'status',
    ];
}
