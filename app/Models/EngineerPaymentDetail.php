<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EngineerPaymentDetail extends Model
{
    use HasFactory;
    protected $table = 'engineer_payment_detail';
    protected $fillable = [
        'user_id',
        'payment_currency',
        'bank_name',
        'account_type',
        'holder_name',
        'account_number',
        'bank_address',
        'iban',
        'swift_code',
        'routing',
        'personal_tax_id',
        'sort_code',
    ];
    
}
