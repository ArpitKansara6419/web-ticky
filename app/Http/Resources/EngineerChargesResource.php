<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EngineerChargesResource extends JsonResource
{ 
    public function toArray(Request $request): array
    {
        return [ 
            'engineer_id' => $this?->engineer_id ?? null,
            'hourly_charge' => $this?->hourly_charge ?? null,
            'half_day_charge' => $this?->half_day_charge ?? null,
            'full_day_charge' => $this?->full_day_charge ?? null,
            'monthly_charge' => $this?->monthly_charge ?? null,
            'check_in_time' => $this?->check_in_time ?? null,
            'check_out_time' => $this?->check_out_time ?? null,
            'currency_type' => $this?->currency_type ?? null,
        ]; 
    }
}