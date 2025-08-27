<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EngineerLanguageSkill extends Model
{
    use HasFactory;


    protected $table = 'engineer_language_skills';

    protected $fillable = [
        'user_id',
        'language_name',
        'proficiency_level',
        'read',
        'write',
        'speak',
    ];
}
