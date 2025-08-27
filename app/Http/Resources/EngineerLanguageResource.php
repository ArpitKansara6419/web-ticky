<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EngineerLanguageResource extends JsonResource
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
            'language_name'     => $this?->language_name ?? '',
            'proficiency_level' => $this?->proficiency_level ?? '',
            'read'              => $this?->read ?? '',
            'write'             => $this?->write ?? '',
            'speak'             => $this?->speak ?? '',
        ];
    }
}
