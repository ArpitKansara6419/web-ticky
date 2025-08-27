<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankTypes extends Model
{
    use HasFactory;

    protected $table = "bank_types";

    protected $fillable = [
        'name'
    ];
}
