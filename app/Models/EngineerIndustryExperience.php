<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Engineer;

class EngineerIndustryExperience extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'experience',
    ];

    public function user()
    {
        return $this->belongsTo(Engineer::class, 'user_id');
    }
    
}
