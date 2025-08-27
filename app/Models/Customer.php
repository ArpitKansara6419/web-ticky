<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'customer_code',
        'customer_type',
        'company_reg_no',
        'address',
        'vat_no',
        'email',
        'auth_person',
        'auth_person_email',
        'auth_person_contact',
        'status',
        'profile_image',
        'doc_ref',
    ];

    public function customerDocs(){
        return $this->hasMany(CustomerDocument::class,'customer_id');
    }

    public function leads(){
        return $this->hasMany(Lead::class,'customer_id');
    }

    public function authorisedPersons()
    {
        return $this->hasMany(CustomerAuthorisedPerson::class);
    }
    
}
