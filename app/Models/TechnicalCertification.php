<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TechnicalCertification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'certification_type',
        'certification_id',
        'certificate_file',
        'issue_date',
        'expire_date',
        'status',
    ];
}
