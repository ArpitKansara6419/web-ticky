<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;

    // Table name (optional, defaults to "holidays")
    protected $table = 'holidays';

    // Fields that can be mass assigned
    protected $fillable = [
        'title', 'date', 'note', 'status', 'country_name', 'country_code', 'type', 'description', 'primary_type', 'holiday_sync_id'];

    // Example of custom accessor/mutator (optional)
    public function getFormattedDateAttribute()
    {
        return date('F d, Y', strtotime($this->date));
    }
}
