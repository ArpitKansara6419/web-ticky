<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EngineerTechnicalCertification extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                    => $this?->id ?? '',
            'user_id'               => (int)$this?->user_id ?? '',
            'certification_type'    => $this?->certification_type ?? '',
            'certification_id'      => $this?->certification_id ?? '',
            'certificate_file'      => $this?->certificate_file ?? '',
            'issue_date'            => $this?->issue_date ?? '',
            'expire_date'           => $this?->expire_date ?? '',
            'status'                => $this?->status ?? '',
        ];
    }
}
