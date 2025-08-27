<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterData extends Model
{
    use HasFactory;

    protected $table = 'master_data';

    // document_type, vehicale_types,  skills, skill_levels, spoken_languages, technical_certification_types

    protected $fillable = [
        'key_name',
        'label_name',
        'type',
        'status',
        'extra'
    ];

}
