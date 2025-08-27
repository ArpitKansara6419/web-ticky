<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EngineerCalendarLeaveRS extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $from = '';
        $to = '';

        if(!empty($this->paid_to_date))
        {
            $from = $this->paid_from_date;
            $to = $this->paid_to_date;
        }else{
            $from = $this->unpaid_from_date;
            $to = $this->unpaid_to_date;
        }
        return [
            'from' => $from,
            'to' => $to
        ];
    }
}
