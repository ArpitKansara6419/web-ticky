<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user_id'          => $this?->user_id ?? '',
            'payment_currency' => $this?->payment_currency ?? '',
            'bank_name'        => $this?->bank_name ?? '',
            'bank_address'     => $this?->bank_address ?? '',
            'account_number'   => $this?->account_number ?? '',
            'account_type'     => $this?->account_type ?? '',
            'holder_name'      => $this?->holder_name ?? '',
            'personal_tax_id'  => $this?->personal_tax_id ?? '',
            'iban'             => $this?->iban ?? '',
            'swift_code'       => $this?->swift_code ?? '',
            'routing'          => $this?->routing ?? '',
            'sort_code'       => $this?->sort_code ?? '',
        ];
    }
}
