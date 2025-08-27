<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class EngineerDocumentsResource extends JsonResource
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
            'user_id'               => $this?->user_id ?? '',
            'document_type'         => $this?->document_type ?? '',
            'issue_date'            => $this?->issue_date ?? '',
            'expiry_date'           => $this?->expiry_date ?? '',
            'media_file'            => $this?->media_file ? json_decode($this?->media_file) : [],
            'status'                => $this?->status ?? ''
        ];
    }
}
