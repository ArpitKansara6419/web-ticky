<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EngineerRightToWork extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user_id'                 => $this?->user_id ?? null,
            'type'                    => $this?->type ?? null,
            'document_type'           => $this?->document_type ?? null,
            'document_file'           => $this?->document_file ?? null,
            'university_certificate_file' => $this?->university_certificate_file ?? null,
            'visa_copy_file'        => $this?->visa_copy_file ?? null,
            'other_name'            => $this?->other_name ?? null,
            'issue_date'            => $this?->issue_date ?? null,
            'expire_date'           => $this?->expire_date ?? null,
            'status'                => $this?->status ?? null,
        ];
    }
}
