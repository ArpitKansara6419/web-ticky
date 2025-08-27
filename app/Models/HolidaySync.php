<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HolidaySync extends Model
{
    use HasFactory;

    protected $table = 'holiday_syncs';

    protected $fillable = [
        'country_name',
        'iso_3166',
        'total_holidays',
        'supported_languages',
        'uuid',
        'flag_unicode',
        'year',
    ];
}
