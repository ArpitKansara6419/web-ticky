<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EngineerDocument extends Model
{
    use HasFactory;

    protected $table = 'engineer_documents';

    protected $fillable = [
        'user_id',
        'document_type',
        'document_label',
        'media_file',
        'issue_date',
        'expiry_date',
        'extra_info',
        'extra_data',
        'status',
    ];
}
