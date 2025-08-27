<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

     
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'first_name'      => $this->first_name,
            'last_name'      => $this->last_name,
            'user_code'      => $this->user_code,
            'email'     => $this->email,
            'contact'     => $this->contact,
            'country_code'     => $this->country_code,
            'profile_image'     => $this->profile_image,
            'email_verification' => $this->email_verification,
            'admin_verification' => $this->admin_verification,
            'phone_verification' => $this->phone_verification,
            'job_type' => $this->job_type,
            'job_title' => $this->job_title,
            'job_start_date' => $this->job_start_date,
            'about' => $this->about,
            'gender' => $this->gender,
            'addr_apartment' => $this->addr_apartment,
            'addr_street' => $this->addr_street,
            'addr_address_line_1' => $this->addr_address_line_1,
            'addr_city' => $this->addr_city,
            'birthdate' => $this->birthdate,
            'nationality' => $this->nationality,
            'right_to_work' => $this->right_to_work,
            'referral' => $this->referral,
            'timezone' => $this->timezone,
        ];
    }
}
