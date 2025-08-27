<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeadResource extends JsonResource
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
            'lead_code' => $this->lead_code,
            'name' => $this->name,
            'customer_id' => $this->customer_id,
            'customer' => new CustomerResource($this->whenLoaded('customer')), // Include customer if loaded
            'lead_type' => $this->lead_type,
            'task_date' => $this->task_date,
            'task_time' => $this->task_time,
            'task_location' => $this->task_location,
            'scope_of_work' => $this->scope_of_work,
            'rate_type' => $this->rate_type,
            'hourly_rate' => $this->hourly_rate,
            'half_day_rate' => $this->half_day_rate,
            'full_day_rate' => $this->full_day_rate,
            'monthly_rate' => $this->monthly_rate,
            'currency_type' => $this->currency_type,
            'lead_status' => $this->lead_status,
            'reschedule_date' => $this->reschedule_date,
            'travel_cost' => $this->travel_cost,
            'tool_cost' => $this->tool_cost,
            'apartment' => $this->apartment,
            'add_line_1' => $this->add_line_1,
            'add_line_2' => $this->add_line_2,
            'zipcode' => $this->zipcode,
            'city' => $this->city,
            'country' => $this->country,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
