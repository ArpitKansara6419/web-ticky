<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EngineerNotificationRS extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'engineer_id' => $this->engineer_id,
            'title' => $this->title,
            'body' => $this->body,
            'notification_type' => $this->notification_type,
            'response_additional_data' => $this->response_additional_data,
            'seen_at' => $this->seen_at,
            'is_seen' => $this->is_seen,
            'created_at' => $this->created_at,
        ];
    }
}
