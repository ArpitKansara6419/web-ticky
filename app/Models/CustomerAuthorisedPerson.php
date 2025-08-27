<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerAuthorisedPerson extends Model
{
    use HasFactory;

    protected $table = "customer_authorised_people";

    protected $fillable = [
        'customer_id',
        'person_name',
        'person_email',
        'person_contact_number',
    ];
}
