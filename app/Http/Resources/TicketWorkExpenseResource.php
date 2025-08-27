<?php 

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketWorkExpenseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this?->id ?? null,
            'ticket_work_id' => $this?->ticket_work_id ?? null,
            'engineer_id' => $this?->engineer_id ?? null,
            'ticket_id' => $this?->ticket_id ?? null,
            'name' => $this?->name ?? null,
            'expense' => $this?->expense ?? null,
            'document' => $this?->document ?? null,
            'status' => $this?->status ?? null,
        ];
    }
}
