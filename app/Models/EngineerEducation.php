<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EngineerEducation extends Model
{
    use HasFactory;
    protected $table = 'engineer_education';

    protected $fillable = [
        'user_id',
        'degree_name',
        'university_name',
        'issue_date',
        'status',
        'media_files',
    ];

}
