<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'customer_code' => $this->customer_code,
            'name' => $this->name,
            'customer_type' => $this->customer_type,
            'company_reg_no' => $this->company_reg_no,
            'address' => $this->address,
            'vat_no' => $this->vat_no,
            'email' => $this->email,
            'auth_person' => $this->auth_person,
            'auth_person_email' => $this->auth_person_email,
            'auth_person_contact' => $this->auth_person_contact,
            'profile_image' => $this->profile_image,
            'doc_ref' => $this->doc_ref,
            'status' => $this->status,
        ];
    }
}
