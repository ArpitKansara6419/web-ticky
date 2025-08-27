<?php 

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HolidayResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this?->id ?? null,
            'title' => $this?->title ?? null,
            'date' => $this?->date ?? null,
            'note' => $this?->note ?? null,
            'status' => $this?->status ?? null
        ];
    }
}
