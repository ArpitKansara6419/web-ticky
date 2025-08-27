<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EngineerProfile extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'engineer_code' => $this->engineer_code,
            'name'          => $this->name,
            'first_name'    => $this->first_name,
            'last_name'     => $this->last_name,
            'username'      => $this->username,
            'email'         => $this->email,
            'contact'       => $this->contact,
            'contact_iso'       => $this->contact_iso,
            'country_code'     => $this->country_code,
            'alternate_country_code' => $this->alternate_country_code,
            'profile_image'     => $this->profile_image,
            'about'     => $this->about,
            'gender'     => $this->gender,
            'alternative_contact'  => $this->alternative_contact,
            'alternate_contact_iso' => $this->alternate_contact_iso,
            'address'  => [
                'apartment' => $this->addr_apartment,
                'street' => $this->addr_street,
                'address_line_1' => $this->addr_address_line_1,
                'address_line_2' => $this->addr_address_line_2,
                'zipcode' => $this->addr_zipcode,
                'city' => $this->addr_city,
                'country' => $this->addr_country,
            ],
            'address_text' => implode(', ', array_filter([
                $this->addr_apartment,
                $this->addr_street,
                $this->addr_address_line_1,
                $this->addr_address_line_2,
                $this->addr_zipcode,
                $this->addr_city,
                $this->addr_country,
            ])),
            'birthdate'     => $this->birthdate,
            'job_type'     => $this->job_type,
            'job_title'     => $this->job_title,
            'payrates' => $this->enggCharge ?? null,
            'extrapayrates' => $this->enggExtraPay ?? null,
            'job_start_date'     => $this->job_start_date,
            'email_verification' => $this->email_verification,
            'admin_verification' => $this->admin_verification,
            'phone_verification' => $this->phone_verification,
            'is_deleted' => $this->is_deleted,
            'gdpr_consent' => $this->gdpr_consent ?? null,
            'timezone' => $this->timezone,
            'device_token' => $this->device_token
        ];
    }
}
