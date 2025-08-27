<?php

namespace App\Http\Requests;

use App\Enums\EngineerResponseEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TaskReminderResponseRequest extends FormRequest
{
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status'   => false,
            'message'   => 'Validation errors',
            'error'      => $validator->errors()->first()
        ]));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_response' => [
                'required',
                Rule::in(array_column(EngineerResponseEnum::cases(), 'value'))
            ],
            'reason' => [
                'required',
                'min:4',
                'max:255'
            ]
        ];
    }
}
