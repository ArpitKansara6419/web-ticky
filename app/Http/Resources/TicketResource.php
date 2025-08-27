<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{ 
    public function toArray(Request $request): array
    {
        $currentDate = now()->toDateString(); 
        // Find the current work ID from ticketWork collection
        $ticketCurrentWork = $this->whenLoaded('ticketWork', function () use ($currentDate) {
            return $this->ticketWork->firstWhere(function ($work) use ($currentDate) {
                return is_null($work->end_time) == $currentDate;
            });
        });

        return [ 
            'id' => $this?->id ?? null,
            'ticket_code' => $this?->ticket_code ?? null,
            'engineer_id' => $this?->engineer_id ?? null,
            'customer_id' => $this?->customer_id ?? null,
            'lead_id' => $this?->lead_id ?? null,
            'engineer' => $this->whenLoaded('engineer', function () {
                return new EngineerProfile($this->engineer);
            }),
            'customer' => $this->whenLoaded('customer', function () {
                return new CustomerResource($this->customer);
            }),
            'lead' => $this->whenLoaded('lead', function () {
                return new LeadResource($this->lead);
            }),
            'ticket_current_work_id' => $ticketCurrentWork?->id ?? null,
            'ticket_works' => TicketWorkResource::collection($this->whenLoaded('ticketWork')),
            'client_name' => $this?->client_name ?? null,
            'client_address' => $this?->client_address ?? null,
            'task_name' => $this?->task_name ?? null,
            'task_start_date' => $this?->ticket_start_date_tz ?? null,
            'task_end_date' => $this?->ticket_end_date_tz ?? null,
            'task_time' => $this?->ticket_time_tz ?? null,
            'scope_of_work' => $this?->scope_of_work ?? null,
            'poc_details' => $this?->poc_details ?? null,
            're_details' => $this?->re_details ?? null,
            'call_invites' => $this?->call_invites ?? null,
            'ref_sign_sheet' => $this?->ref_sign_sheet ?? null,
            'documents' => $this?->documents ?? null,
            'rate_type' => $this?->rate_type ?? null,
            'standard_rate' => $this?->standard_rate ?? null,
            'travel_cost' => $this?->travel_cost ?? null,
            'tool_cost' => $this?->tool_cost ?? null,
            'food_expenses' => $this?->food_expenses ?? null, 
            'misc_expenses' => $this?->misc_expenses ?? null,
            'currency_type' => $this?->currency_type ?? null,
            'status' => $this?->status ?? null,
            'apartment' => $this?->apartment ?? null,
            'add_line_1' => $this?->add_line_1 ?? null,
            'add_line_2' => $this?->add_line_2 ?? null,
            'city' => $this?->city ?? null,
            'country' => $this?->country ?? null,
            'zipcode' => $this?->zipcode ?? null,
            'engineer_status' => $this?->engineer_status ?? null,
            'latitude' => $this?->latitude ?? null,
            'longitude' => $this?->longitude ?? null,
            'ticket_start_date_tz' => $this?->ticket_start_date_tz ?? null,
            'ticket_end_date_tz' => $this?->ticket_end_date_tz ?? null,
            'ticket_time_tz' => $this?->ticket_time_tz ?? null,

            'is_engineer_agreed_rate' => $this?->is_engineer_agreed_rate ?? null,
            'engineer_agreed_rate' => $this?->engineer_agreed_rate ?? null,
        ];
    }
}