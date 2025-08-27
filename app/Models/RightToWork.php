<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RightToWork extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'document_type',
        'document_file',
        'university_certificate_file',
        'visa_copy_file',
        'other_name',
        'issue_date',
        'expire_date',
        'status',
    ];
}
