<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeaveApplicationResource extends JsonResource
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
            'id' => $this?->id ?? null,
            'engineer_id' => $this->engineer_id,
            'paid_from_date' => $this?->paid_from_date ?? null,
            'paid_to_date' => $this?->paid_to_date ?? null,
            'unpaid_from_date' => $this?->unpaid_from_date ?? null,
            'unpaid_to_date' => $this?->unpaid_to_date ?? null,
            'paid_leave_days' => $this?->paid_leave_days ?? null,
            'unpaid_leave_days' => $this?->unpaid_leave_days ?? null,
            'unpaid_reason' => $this?->unpaid_reason ?? null,
            'note' => $this?->note ?? null,
            'paid_document' => $this?->paid_document ?? null,
            'unpaid_document' => $this?->unpaid_document ?? null,
            'leave_approve_status' => $this?->leave_approve_status ?? null,
            'signed_paid_document' => $this?->signed_paid_document ?? null,
            'unsigned_paid_document' => $this?->unsigned_paid_document ?? null,
            'signed_unpaid_document' => $this?->signed_unpaid_document ?? null,
            'unsigned_unpaid_document' => $this?->unsigned_unpaid_document ?? null,
        ];
    }
}
