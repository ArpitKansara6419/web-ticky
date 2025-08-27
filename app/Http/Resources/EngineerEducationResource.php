<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EngineerEducationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this?->id ?? '',
            'user_id'           => $this?->user_id ?? '',
            'degree_name'       => $this?->degree_name ?? '',
            'university_name'   => $this?->university_name ?? '',
            'issue_date'        => $this?->issue_date ?? '',
            'media_files'       => $this?->media_files ? json_decode($this?->media_files) : [],
            'status'            => $this?->status ?? '',
        ];
    }
}
