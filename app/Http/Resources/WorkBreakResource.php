<?php 

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkBreakResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this?->id ?? null,
            'ticket_work_id' => $this?->ticket_work_id ?? null,
            'ticket_id' => $this?->ticket_id ?? null,
            'engineer_id' => $this?->engineer_id ?? null,
            'work_date' => $this?->work_date_timezone ?? null,
            'break_start_date' => !empty($this->break_start_date) ? $this?->break_start_date_timezone->format('Y-m-d') : null,
            'break_end_date' => !empty($this->break_end_date) ? $this?->break_end_date_timezone->format('Y-m-d') : null,
            'start_time' => !empty($this->start_time) ? $this?->start_time_timezone->format('H:i:s') : null,
            'end_time' => !empty($this->end_time) ? $this?->end_time_timezone->format('H:i:s') : null,
            'total_break_time' => $this?->total_break_time ?? null,
            'status' => $this?->status ?? null,
        ];
    }
}
