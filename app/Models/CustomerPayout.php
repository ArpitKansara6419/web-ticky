<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerPayout extends Model
{
    use HasFactory;

    protected $table = 'customer_payout';

    protected $fillable = [
        'customer_id',
        'invoice_number',
        'from_date',
        'to_date',
        'customer_payable_ids',
        'total_payable',
        'extra_incentive',
        'gross_pay',
        'currency',
        'note',
        'payment_date',
        'payment_status',
        'payment_type',
        'bank_id',
        'bank_details',
    ];

    protected $casts = [
        'customer_payable_ids' => 'array',
        'from_date' => 'date',
        'to_date' => 'date',
        'payment_date' => 'date',
        'total_payable' => 'float',
        'extra_incentive' => 'float',
        'bank_details' => 'array',
    ];

    protected static function booted()
    {
        static::creating(function ($payout) {
            $payout->invoice_number = self::generateInvoiceNumber();
        });
    }

    public static function generateInvoiceNumber()
    {
        $currentYear = now()->format('Y');
        $currentMonth = now()->format('m');
        $currentDay = now()->format('d');
        
        $invoiceCount = self::whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->count();
        
        $invoiceNumber = $invoiceCount + 1;
        
        return sprintf(
            '%02d%s%s%s',
            $invoiceNumber,
            $currentYear,
            $currentMonth,
            $currentDay
        );
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    
}
