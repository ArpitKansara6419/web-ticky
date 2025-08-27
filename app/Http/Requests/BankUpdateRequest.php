<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @method mixed input(string $key = null, mixed $default = null)
 */
class BankUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'bank_name' => [
                'required',
                'min:3',
                'max:299',
            ],
            'bank_address' => [
                'required',
            ],
            'account_holder_name' => [
                'required',
            ],
            'account_number' => [
                'required',
            ],
            'iban' => [
                'nullable'
            ],
            'swift_code' => [
                'nullable',
            ],
            'sort_code' => [
                'nullable',
            ],
            'country' => [
                'required'
            ]
        ];
    }

    public function processData(): array
    {
        return [
            'bank_name' => $this->input('bank_name'),
            'bank_address' => $this->input('bank_address'),
            'account_holder_name' => $this->input('account_holder_name'),
            'account_number' => $this->input('account_number'),
            'iban' => $this->input('iban'),
            'swift_code' => $this->input('swift_code'),
            'sort_code' => $this->input('sort_code'),
            'country' => $this->input('country'),
        ];
    }
}
