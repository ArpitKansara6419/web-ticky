<?php 

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketWorkResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this?->id ?? null,
            'ticket_id' => $this?->ticket_id ?? null,
            'user_id' => $this?->user_id ?? null,
            'work_start_date' => $this?->work_start_date_timezone ?? null,
            'work_end_date' => $this?->work_end_date_timezone ?? null,
            'start_time' => $this?->start_time_timezone ?? null,
            'end_time' => $this?->end_time_timezone ?? null,
            'total_work_time' => $this?->total_work_time ?? null,
            'hourly_rate'  => $this?->hourly_rate ?? null,
            'halfday_rate' => $this?->halfday_rate ?? null,
            'fullday_rate' => $this?->fullday_rate ?? null,
            'monthly_rate' => $this?->monthly_rate ?? null,
            'hourly_payable' => $this?->hourly_payable ?? null,
            'overtime_payable' => $this?->overtime_payable ?? null,
            'weekend_payable' => $this?->weekend_payable ?? null,
            'holiday_payable' => $this?->holiday_payable ?? null,
            'is_overtime'  => $this?->is_overtime ?? null, 
            'is_overtime_approved'  => $this?->is_overtime_approved ?? null,
            'is_holiday'  => $this?->is_holiday ?? null, 
            'is_weekend'  => $this?->is_weekend ?? null,
            'is_out_of_office_hours'  => $this?->is_out_of_office_hours ?? null, 
            'overtime_hour' => $this?->overtime_hour ?? null,
            'out_of_office_duration' => $this?->out_of_office_duration ?? null,
            'out_of_office_payable' => $this?->out_of_office_payable ?? null,
            'other_pay' => $this?->other_pay ?? null,
            'engineer_payout_status'  => $this?->engineer_payout_status ?? null,
            'status' => $this?->status ?? null,
              // Include breaks relationship if it is loaded
            'breaks' => WorkBreakResource::collection($this->whenLoaded('breaks')),
            'workexpense' => TicketWorkExpenseResource::collection($this->whenLoaded('workExpense')),
            'ticket_work_notes' => DailyWorkNoteResource::collection($this->whenLoaded('workNote')),
        ];
    }
}
