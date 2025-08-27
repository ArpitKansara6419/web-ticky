<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    protected $table = 'banks';

    protected $fillable = [
        'bank_type_id',
        'bank_name',
        'bank_address',
        'account_holder_name',
        'account_number',
        'iban',
        'swift_code',
        'sort_code',
        'country',
    ];

    public function scopeActiveBank($query)
    {
        return $query
            ->where('is_active', '1');
    }
}
