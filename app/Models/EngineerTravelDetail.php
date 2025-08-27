<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EngineerTravelDetail extends Model
{
    use HasFactory;

    protected $table = 'engineer_travel_detail';

    protected $fillable = [
        'user_id',
        'driving_license',
        'own_vehicle',
        'working_radius',
        'type_of_vehicle',
    ];

    
}
